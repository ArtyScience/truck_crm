<script setup>
import { ScheduleXCalendar } from '@schedule-x/vue'
import { createEventModalPlugin } from '@schedule-x/event-modal'
import {
  createCalendar,
  createViewDay,
  createViewMonthAgenda,
  createViewMonthGrid,
  createViewWeek,
} from '@schedule-x/calendar'
import '@schedule-x/theme-default/dist/index.css'

const eventModal = createEventModalPlugin()

const currentDate = new Date();
const year = currentDate.getFullYear();
const month = String(currentDate.getMonth() + 1).padStart(2, '0');
const day = String(currentDate.getDate()).padStart(2, '0');

const calendarApp = createCalendar({
  plugins: [eventModal],
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
      title: 'Call client Michael',
      start: '2025-02-24',
      end: '2025-02-28',
    },
    {
      id: 2,
      title: 'Event 2',
      start: '2023-12-20 12:00',
      end: '2023-12-20 13:00',
    },
  ],
})
</script>

<script>
export default {
  name: "Calendar_X",
  methods: {

  },

}
</script>

<template>
  <div>
    <ScheduleXCalendar :calendar-app="calendarApp" />
  </div>
</template>

<style scoped lang="scss">

</style>