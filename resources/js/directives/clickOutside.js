export default {
    beforeMount(el, binding) {
        el.clickOutsideEvent = (event) => {
            // Check if the click was outside the element
            if (!(el === event.target || el.contains(event.target))) {
                binding.value(event);
            }
        };
        // Add the event listener to the document
        document.addEventListener('click-outside', el.clickOutsideEvent);
    },
    beforeUnmount(el) {
        // Remove the event listener when the element is unmounted
        document.removeEventListener('click-outside', el.clickOutsideEvent);
    }
};
