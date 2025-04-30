import {toast} from "vue3-toastify";
const theme = localStorage.getItem('theme')

export default {
    success(message) {
        toast(message, {
            theme: theme,
            autoClose: 2000,
            type: 'success',
            position: 'top-right'
        });
    },
    error(message, position = 'top-center') {
        toast(message, {
            theme: theme,
            autoClose: 2000,
            type: 'error',
            position: position
        });
    },
    info(message, position = 'top-center') {
        toast(message, {
            theme: theme,
            autoClose: 2000,
            type: 'info',
            position: position,
            limit: 1,
            toastStyle: '#e1e0e0'
        });
    },
    validationError(html, position = 'top-center') {
        toast(html, {
            dangerouslyHTMLString: true,
            theme: theme,
            type: 'error',
            autoClose: 2000,
            limit: 1,
            position: position
        });
    }
}
