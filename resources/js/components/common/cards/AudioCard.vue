<script>
export default {
    name: "AudioCard",
    data() {
        return {
            audio_items: [
                {
                    title: 'Arthur call to Andrew',
                    audio: 'https://audio.transistor.fm/m/shows/15/dai/67e8213447a11492c2bd33d777d596bf.mp3'
                },
                {
                    title: 'Igor call to Samanta',
                    audio: 'https://audio.transistor.fm/m/shows/15/dai/67e8213447a11492c2bd33d777d596bf.mp3'
                },
            ]
        }
    }
}
</script>

<template>
<div class="audio_card_wrapper">

    <table style="border: 1px solid transparent; max-width: 820px; border-radius: 10px; color: #fff; line-height: .4; font-family: InterVariable, ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';" bgcolor="#87387b" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr height="10"></tr>
        <tr v-for="item in audio_items">
            <td width="15"></td>
            <td width="15"></td>
            <td>
                <h3 class="mb-2 mt-4">{{ item.title }}</h3>
                <audio controls style="width: 100%; opacity: .7;">
                    <a :href="item.audio" style="font-family: monospace; text-decoration: none; color: #fff;">
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
                                            <td align="right">
                                                <small style="color: #ffffff; opacity:0.7;)">00:00</small>
                                                <small style="color: #ffffff; opacity:0.2;)">|</small>
                                                <small style="color: #ffffff; opacity:0.7;)">01:12:00</small>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </a>
                    <source :src="item.audio" type="audio/mpeg"></source>
                </audio>
            </td>
            <td width="15"></td>
        </tr>
        <tr height="7.5"></tr>
    </table>
</div>
</template>

<style scoped lang="scss">

</style>
