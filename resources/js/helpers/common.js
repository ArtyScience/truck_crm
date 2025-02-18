export default {
    formErrors(errors, errors_text) {
        for (const errorsKey in errors) {
            errors_text[errorsKey] = errors[errorsKey][0]
            errors[errorsKey] = true
        }
        let html = ''
        for (let key in errors_text) {
            if (errors_text.hasOwnProperty(key)) {
                html += errors_text[key] + '<br/>'
            }
        }

        return { html, errors, errors_text }
    },
    cleanActivity(activity) {
        Object.keys(activity)
            .forEach(action => activity[action] = false);
    },
}
