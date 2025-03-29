<script>
/*TODO: Refacotr entire component*/
import {AdjustmentsIcon, BellIcon, MoonIcon, SunIcon, CalendarIcon} from '@heroicons/vue/solid'
import axios from 'axios';
import Modal from "../core/Modal.vue";
import CallendarWrapper from "./CallendarWrapper.vue";

export default {
  mounted() {
    const targetElement = this.$refs.target;
    this.target = targetElement;

    const savedTheme = localStorage.getItem('theme');
    this.dark_theme = savedTheme === 'dark' ||
        (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches);
    document.documentElement.classList.toggle('dark', this.dark_theme)
  },
  name: "Settings",
  data() {
    return {
      show: false,
      dark_theme: false,
      actions: {
        show_modal: false,
      },
      modal_title: ''
    }
  },
  components: {
    CallendarWrapper,
    Modal,
    AdjustmentsIcon, BellIcon, MoonIcon, SunIcon, CalendarIcon
  },
  methods: {
    openCalendar() {
      this.modal_title = 'Show calendar'
      this.actions.show_modal = true
    },
    changeTheme() {
      this.dark_theme = !this.dark_theme
      document.documentElement.classList
          .toggle('dark', this.dark_theme)
      const theme = this.dark_theme ? 'dark' : 'light'
      localStorage.setItem('theme', theme)
      const url = 'api/v1/settings/save-theme'
      axios.post(url, {
        theme: theme
      })
    },
    addEvent(name, event) {
      document.addEventListener(
          name,
          event
      );
    },
    removeEvent(name, event) {
      document.removeEventListener(
          name,
          event
      );
    },
    closeModal() {
      this.actions.show_modal = false
    },
    openModal() {
      if (!this.show) {
        this.show = true
        setTimeout(() => {
          this.addEvent('click', this.clickOutside)
        }, 150)
      }
    },
    openNotifications() {
    },
    clickOutside(event) {
      if (this.$refs.target &&
          !this.$refs.target.contains(event.target)) {
        this.show = false;
        this.removeEvent('click', this.clickOutside)
      }
    },
    logOut() {
      const apiUrl = '/logout';
      const csrfTokenMetaTag = document.querySelector('meta[name="csrf-token"]');
      const csrfToken = csrfTokenMetaTag ? csrfTokenMetaTag.getAttribute('content') : null;

      const headers = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
      };

      axios.post(apiUrl, {}, {headers})
          .then((response) => {
            window.location.href = '/login'
          })
          .catch((error) => {
            // Handle errors
            console.error('Error:', error);
            /*TODO: Security Alert if missmatch Token*/
            /*TODO: Write Log on token missmatch*/
          });
    }
  }
}
</script>

<template>
  <div id="settings_wrapper"
       class="sm:flex sm:items-center sm:ml-6">

    <Modal
        @update:state="closeModal"
        :title="modal_title"
        :state="actions.show_modal">
      <template #body>
        <div v-if="actions.show_modal" class="calendar_wrapper">
          <CallendarWrapper :state="actions.show_modal" />
        </div>
      </template>
    </Modal>

    <div class="relative">
      <div class="top_buttons">
        <button @click="changeTheme()" type="button"
                class="inline-flex text-gray-500 dark:text-white hover:text-blue-800"
                aria-expanded="false">
          <div class="icon_wrapper">
            <MoonIcon v-if="!dark_theme"/>
            <SunIcon v-else/>
          </div>
        </button>
        <button id="notifyButton" @click="openNotifications()" type="button"
                class="inline-flex text-gray-500 dark:text-white hover:text-blue-800"
                aria-expanded="false">
          <div class="icon_wrapper">
            <BellIcon/>
          </div>
        </button>
        <button @click="openCalendar()" type="button"
                class="inline-flex text-gray-500 dark:text-white hover:text-blue-800"
                aria-expanded="false">
          <div class="icon_wrapper">
            <CalendarIcon/>
          </div>
        </button>
        <button @click="openModal()" type="button"
                class="inline-flex text-gray-500 dark:text-white hover:text-blue-800"
                aria-expanded="false">
          <div class="icon_wrapper">
            <AdjustmentsIcon/>
          </div>
        </button>
      </div>

      <!--
        Flyout menu, show/hide based on flyout menu state.

        Entering: "transition ease-out duration-200"
          From: "opacity-0 translate-y-1"
          To: "opacity-100 translate-y-0"
        Leaving: "transition ease-in duration-150"
          From: "opacity-100 translate-y-0"
          To: "opacity-0 translate-y-1"
      -->
      <div v-if="show" ref="target"
           class="absolute right-0 z-30">
        <div style="width: 160px"
             class="rounded w-screen max-w-md flex-auto overflow-hidden bg-white text-sm leading-6 shadow-lg ring-1 ring-gray-900/5">
          <div class="p-2">
            <div class="hidden group relative flex gap-x-6 rounded-lg p-0 hover:bg-gray-50">
              <div
                  class="mt-1 flex h-11 w-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                <svg class="h-6 w-6 text-gray-600 group-hover:text-indigo-600" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/>
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/>
                </svg>
              </div>
              <div class="text_wrapper">
                <a href="#" class="font-semibold text-gray-900 group-hover:text-indigo-600">
                  Analytics
                  <span class="absolute inset-0"></span>
                </a>
              </div>
            </div>
            <div class="hidden group relative flex gap-x-6 rounded-lg p-0 hover:bg-gray-50">
              <div
                  class="mt-1 flex h-11 w-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-600 group-hover:text-indigo-600">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                </svg>
              </div>
              <div class="text_wrapper">
                <a href="#" class="font-semibold text-gray-900 group-hover:text-indigo-600">
                  Rating
                  <span class="absolute inset-0"></span>
                </a>
              </div>
            </div>
            <div class="hidden group relative flex gap-x-6 rounded-lg p-0 hover:bg-gray-50">
              <div
                  class="mt-1 flex h-11 w-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                <svg class="h-6 w-6 text-gray-600 group-hover:text-indigo-600" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7.864 4.243A7.5 7.5 0 0119.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 004.5 10.5a7.464 7.464 0 01-1.15 3.993m1.989 3.559A11.209 11.209 0 008.25 10.5a3.75 3.75 0 117.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 01-3.6 9.75m6.633-4.596a18.666 18.666 0 01-2.485 5.33"/>
                </svg>
              </div>
              <div class="text_wrapper">
                <a href="#" class="font-semibold text-gray-900 group-hover:text-indigo-600">
                  Security
                  <span class="absolute inset-0"></span>
                </a>
              </div>
            </div>
            <div class="hidden group relative flex gap-x-6 rounded-lg p-0 hover:bg-gray-50">
              <div
                  class="mt-1 flex h-11 w-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-600 group-hover:text-indigo-600">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/>
                </svg>
              </div>
              <div class="text_wrapper">
                <a href="#" class="font-semibold text-gray-900 group-hover:text-indigo-600">
                  Suport
                  <span class="absolute inset-0"></span>
                </a>
              </div>
            </div>
            <div class="group relative flex gap-x-6 rounded-lg p-0 hover:bg-gray-50">
              <div
                  class="mt-1 flex h-11 w-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-600 group-hover:text-indigo-600">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                </svg>
              </div>
              <div class="text_wrapper">
                <a @click="logOut()" class="font-semibold text-gray-900 group-hover:text-indigo-600">
                  Log out
                  <span class="absolute inset-0"></span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">

#settings_wrapper {

  //.top_buttons {
  //    .notify_btn {
  //        .icon_wrapper {
  //            color: lightslategray;
  //
  //            &.active {
  //                color: #f3f3f3;
  //            }
  //        }
  //    }
  //}

  .top_buttons {
    button {
      &:first-child {
        //display: none;
      }

      &:nth-child(2) {
        //display: none;
      }
    }
  }

  button {
    padding: 5px;
    margin-top: 15px;

    .icon_wrapper {
      height: 25px;
      width: 20px;
    }
  }

  .text_wrapper {
    //padding: 7px 7px 0px 11px;
    text-align: center;
    padding-top: 13px;

    a {
      cursor: pointer;
    }
  }
}
</style>
