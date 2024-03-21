
//require('tabler-ui/dist/assets/js/require.min');

require('./bootstrap');
require('./components/helpers');
require('./init-vue');

import * as form from './components/form';
import * as parsley from './components/parsley';
import * as bootstrapFileinput from './components/bootstrap-fileinput';
import * as crawlerDashboard from './components/crawler-dashboard';
import * as modal from './components/modal';
import * as toggleFields from './components/toggle-fields';
import * as tooltip from './components/tooltip';
import * as dateInput from './components/date-input';

$(function() {
    form.init();
    parsley.init();
    bootstrapFileinput.init();
    crawlerDashboard.init();
    modal.init();
    toggleFields.init();
    tooltip.init();
    dateInput.init();
});
