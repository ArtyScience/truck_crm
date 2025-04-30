<script>
export default {
  name: "AudioCard",
  props: {
    audio: [Array, Object]
  },
  mounted() {
  },
  methods: {
    parseDateTime(date_time) {
      // Input datetime string
      const datetimeString = date_time;

// Step 1: Parse the datetime string
      const date = new Date(datetimeString);

// Step 2: Extract the components
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
        style="border: 1px solid transparent; max-width: 820px; border-radius: 10px; color: #fff; line-height: .4; font-family: InterVariable, ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';"
        bgcolor="#87387b" cellspacing="0" cellpadding="0" border="0" width="100%">
      <tr height="10"></tr>
      <tr v-for="item in audio">
        <td width="15"></td>
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
    </table>
  </div>
</template>

<style scoped lang="scss">
  .audio_card_wrapper {
    max-height: 500px;
    overflow: scroll;
  }
</style>
