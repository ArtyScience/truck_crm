<script setup>
import '@schedule-x/theme-default/dist/index.css'
import '@sx-premium/interactive-event-modal/index.css'

import { createEventsServicePlugin } from "@schedule-x/events-service";
import { ScheduleXCalendar } from '@schedule-x/vue'
import {createInputField, createInteractiveEventModal}
  from "@sx-premium/interactive-event-modal";
import {
  createCalendar,
  createViewDay,
  createViewMonthAgenda,
  createViewMonthGrid,
  createViewWeek,
} from '@schedule-x/calendar'

let some_id = 3

//get user leads suka

const currentDate = new Date();
const year = currentDate.getFullYear();
const month = String(currentDate.getMonth() + 1).padStart(2, '0');
const day = String(currentDate.getDate()).padStart(2, '0');

const eventsService = createEventsServicePlugin()

//get available people for Callendar
// const response = await axios.get('api/v1/leads/get-leads', {
//
// });

console.log('response');

const eventModal = createInteractiveEventModal({
  eventsService,
  availablePeople: ['John Doe', 'Jane Doe', 'ARthur Q'],
  onAddEvent: (event) => {
    console.log('event', event);

    const url = "api/v1/calendar";

    axios.post(url, event)
        .then(response => {
          console.log("Success:", response.data);
        })
        .catch(error => {
          console.error("Error:", error.response ? error.response.data : error.message);
        });
  },
  onDeleteEvent: (eventId) => {
    console.log('eventId', eventId)
  },
  onStartUpdate(start) {
    console.log('start update', start)
  },
  onEndUpdate(end) {
    console.log(end)
  },
  canOpenModal: (event) => {
    console.log('event QQQ', event);
    return event.calendarId === 'calendar-1';
  },
  people(event) {
    console.log('peopleSelect', event);
  },
  movable: true,
  hideTitle: false,
  fields: {
    title: {
      label: 'Event Title',
      name: 'event-title',
      validator: (value) => {
        return {
          isValid: !!value && value.length >= 3,
          message: 'Title must be at least 3 characters long'
        }
      }
    },
    description: {},
    people:[]
  },
  datePicker: {
    min: `${year}-${month}-${day}`,
    max: '2025-12-31',
  }
})

const calendarApp = createCalendar({
  plugins: [eventModal, eventsService],
  selectedDate: `${year}-${month}-${day}`,
  views: [
    createViewDay(),
    createViewWeek(),
    createViewMonthGrid(),
    createViewMonthAgenda(),
  ],
  events: [
    {
      id: 1,
      title: 'Call client Michael (4 days event)',
      start: '2025-02-24',
      end: '2025-02-28',
    },
    {
      id: 2,
      title: 'Call client Arthur',
      start: '2025-02-25 12:00',
      end: '2025-02-25 13:00',
    },
  ],
  callbacks: {
    onDoubleClickDateTime(dateTime) {
      eventModal.clickToCreate(dateTime, {
        id: some_id++,

      })
      console.log('here');
    },
    onEventUpdate(event) {
      console.log('event update', event);
    }

  }
})
</script>

<script>
export default {
  name: "Calendar",
  methods: {},
}
</script>

<template>
  <div >
    <ScheduleXCalendar
        style="height: 600px"
        :calendar-app="calendarApp"/>
  </div>
</template>


<style>
.sx__app-combobox__list.is-open{
  z-index: 100 !important;
  background-color: #fff !important;
}
.sx__date-grid-event-text {

}

.sx__event.sx__date-grid-event.sx__date-grid-cell {
  background-color: #6750a4 !important;
  color: white !important;
  text-align: center;
  padding: 5px !important;
  height: 25px;
  border-left: 4px solid red !important;
}

.sx__week-grid__date-number {
  font-size: 13px !important;
}


</style>
