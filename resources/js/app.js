import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// flatpickr
import flatpickr from 'flatpickr/dist/flatpickr.min.js';
import { Japanese } from "flatpickr/dist/l10n/ja.js";

flatpickr('#js-datepicker', {
    locale : Japanese,
    dateFormat : 'Y/n/j',
    defaultDate : new Date()
});