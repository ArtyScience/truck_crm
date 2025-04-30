export default {
	uuid () {
		return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
			(c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
		);
	},
	async copyToClipboard(textToCopy) {
		if (navigator.clipboard && window.isSecureContext) {
			await navigator.clipboard.writeText(textToCopy);
		} else {
			const textArea = document.createElement("textarea");
			textArea.value = textToCopy;

			textArea.style.position = "absolute";
			textArea.style.left = "-999999px";

			document.body.prepend(textArea);
			textArea.select();

			try {
				document.execCommand('copy');
			} catch (error) {
				console.error(error);
			} finally {
				textArea.remove();
			}
		}
	},
    compareObjects(obj1, obj2) {
        const keys1 = Object.keys(obj1);
        const keys2 = Object.keys(obj2);

        if (keys1.length !== keys2.length) return false;

        for (let key of keys1) {
            if (obj1[key] !== obj2[key]) return false;
        }

        return true;
    }
}
