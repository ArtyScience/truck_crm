<script>
import NoCallsImg from '../../../../../public/images/icons/no_calls.svg'

export default {
  name: "AudioCard",
  components: {
    NoCallsImg
  },
  props: {
    audio: [Array, Object]
  },
  data() {
    return {
      no_phone: NoCallsImg
    }
  },
  mounted() {
  },
  methods: {
    parseDateTime(date_time) {
      const datetimeString = date_time;
      const date = new Date(datetimeString);
      const hours = date.getUTCHours(); // Use getUTCHours() for UTC hours
      const minutes = date.getUTCMinutes(); // Use getUTCMinutes() for UTC minutes
      const year = date.getUTCFullYear(); // Use getUTCFullYear() for UTC year
      const month = date.getUTCMonth() + 1; // getUTCMonth() returns 0-based month (0 = January)
      const day = date.getUTCDate(); // Use getUTCDate() for UTC day

// Step 3: Format the output
      const formattedTime = `${hours}:${minutes.toString().padStart(2, '0')} ${year}:${month.toString().padStart(2, '0')}:${day.toString().padStart(2, '0')}`;

      return formattedTime
    }
  },
}
</script>

<template>
  <div class="audio_card_wrapper">
    <table
        v-if="audio.length > 0"
        class="audio_card_content" border="0" width="100%">
     <tbody>
     <tr height="10"></tr>
     <tr
         v-for="item in audio"
     >
       <td width="15">{{item.length}}</td>
       <td width="15"></td>
       <td>
         <h3 class="mb-2 mt-4">{{ item.from }} -> {{ item.to }} ({{ item.status }}) </h3>
         <p class="mb-2 mt-4">TIME: {{ parseDateTime(item.started_at) }}</p>
         <audio controls style="width: 100%; opacity: .7;">
           <a
               :src="'/calls_records/' + item.file_path"
               style="font-family: monospace; text-decoration: none; color: #fff;">
             <table cellspacing="0" cellpadding="0">
               <tbody>
               <tr>
                 <td>
                   <!--                                    <img width="47" height="47" src="https://marketing.transistor.fm/assets/email-embed-player/play.png" />-->
                 </td>
                 <td width="10px"></td>
                 <td>
                   <div>
                     <!--                                        <img width="100%" src="https://marketing.transistor.fm/assets/email-embed-player/progress-bar.png" />-->
                   </div>
                   <table width="100%" cellspacing="0" cellpadding="0">
                     <tbody>
                     <tr height="7.5"></tr>
                     <tr>
                       <td width="100">
                         <!--                                                <img height="19" width="100" src="https://marketing.transistor.fm/assets/email-embed-player/controls.png" />-->
                       </td>
                       <!--                                            <td align="right">-->
                       <!--                                                <small style="color: #ffffff; opacity:0.7;)">00:00</small>-->
                       <!--                                                <small style="color: #ffffff; opacity:0.2;)">|</small>-->
                       <!--                                                <small style="color: #ffffff; opacity:0.7;)">01:12:00</small>-->
                       <!--                                            </td>-->
                     </tr>
                     </tbody>
                   </table>
                 </td>
               </tr>
               </tbody>
             </table>
           </a>
           <source :src="'/calls_records/' + item.file_path" type="audio/mpeg"></source>
         </audio>
       </td>
       <td width="15"></td>
     </tr>
     <tr height="7.5"></tr>
     </tbody>
    </table>
    <div v-else class="flex justify-center">
      <p class="text-right ml-3 mr-10">You do not have any calls yet</p>
      <div v-if="no_phone" class="text-left"><img :src="no_phone"></div>
    </div>
  </div>
</template>

<style scoped lang="scss">
  .audio_card_wrapper {
    margin-top: 30px;
    max-height: 500px;


    .audio_card_content {
      overflow-y: scroll;
      &::-webkit-scrollbar {
        width: 8px;
      }
      /* Scrollbar Track Background */
      &::-webkit-scrollbar-track {
        background: #374151;
        border-radius: 6px;
      }
      /* Scrollbar Thumb */
      &::-webkit-scrollbar-thumb {
        background-color: #1F2937;
        border-radius: 6px;
        border: 1px solid #0B4161; /* Space around thumb */
      }
      /* Scrollbar Thumb on Hover */
      &::-webkit-scrollbar-thumb:hover {
        background-color: #555;
      }

      background-color: #004D61;
      border: 1px solid transparent;
      max-width: 820px;
      border-radius: 10px;
      color: #fff;
      line-height: .4;
    }
  }
</style>
