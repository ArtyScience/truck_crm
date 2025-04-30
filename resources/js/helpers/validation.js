import {value} from "lodash/seq";

export default {
    isValidZipCode (zip_code) {
        const zipCodeRegex = /^\d{5}(-\d{4})?$|^[A-Za-z]\d[A-Za-z] ?\d[A-Za-z]\d$/;
        return zipCodeRegex.test(zip_code);
    },
	email (email) {
		return String(email)
			.toLowerCase()
			.match(
				/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
			);
	},
	isValidDate(dateString) {
		const regex = /^\d{4}-\d{2}-\d{2}$/;
		if (!dateString.match(regex)) {
			return false;
		}

		const [year, month, day] = dateString.split('-').map(Number);
		const date = new Date(year, month - 1, day);
		if (date.getFullYear() !== year || date.getMonth() !== month - 1 || date.getDate() !== day) {
			return false;
		}

		return true;
	},
	required: value => !!value || 'Required',
	validAddress: value => !!value || 'Please provide an address that has postal code',
	validName: value => (value && /^[a-z ,.'-]+$/i.test(value)) || 'Invalid name',
	validEmail: value => (value && /^[\w-\.]+@([\w-]+\.)+[\w-]{2,24}$/.test(value)) || 'E-mail must be valid',
	validPhone: value => (value && (value.length === 10 && Number.isInteger(Number(value)))) || 'Phone number must contain 10 digits',
	validMc: value => (value && (value.length >= 5 && value.length <= 7) && Number.isInteger(Number(value))) || 'Must be between 5 and 7 digits',
	notRequiredMc: value => {
		if (value == '') {
			return true
		} else {
			return (value && (value.length >= 5 && value.length <= 7)
					&& Number.isInteger(Number(value)))
					|| 'Must be between 5 and 7 digits'
		}
	},
	validDot: value => (value && (value.length >= 6 && value.length <= 8)
						&& Number.isInteger(Number(value)))
						|| 'Must be between 6 and 8 digits',
	notRequiredDot: value => {
		if (value == '') {
			return true
		} else {
			return (value && (value.length >= 6 && value.length <= 8)
					&& Number.isInteger(Number(value)))
					|| 'Must be between 6 and 8 digits'
		}
	},
	validBankInstitutionNumber: value => (value && value.length === 4 && Number.isInteger(Number(value))) || 'Must contain 4 digits',
	validBankAccountNumber: value => (value && (value.length === 7 || value.length === 9) && Number.isInteger(Number(value))) || 'Must contain 7 (USA) or 9 digits (Canada)',
	validBankTransitNumber: value => (value && value.length === 5 && Number.isInteger(Number(value))) || 'Must contain 5 digits',
	validAbaNumber: value => (value && value.length === 9 && Number.isInteger(Number(value))) || 'Must contain 9 digits',
	validFileFormat: value => {
		const error_text = 'Invalid file format. Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG'
		if (!value || value === null) return error_text
		const allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
		const fileExtension = value.name.split('.').pop().toLowerCase();
		return allowedExtensions.includes(fileExtension) || error_text
	},
	validRegNr: value => (value && value.length === 4 && Number.isInteger(Number(value))) || 'Must contain 4 digits',
	validBankName: value => {
		const regex = /\s{2,}/
		let error_text = '';
		if (value === null || value.length < 5) {
			error_text = 'Bank name shoud have more than 5 letters'
		}
		if (value !== null && regex.test(value)) {
			error_text = 'Bank name shoud have only 1 space bettwen words'
		}
		return error_text ?? true
	},
}
