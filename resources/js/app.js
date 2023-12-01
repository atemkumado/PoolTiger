import './bootstrap';

import Alpine from 'alpinejs';
import './../../vendor/power-components/livewire-powergrid/dist/powergrid'

// If you use Tailwind
import './../../vendor/power-components/livewire-powergrid/dist/tailwind.css'

// If you use Bootstrap 5
import './../../vendor/power-components/livewire-powergrid/dist/bootstrap5.css'
// Reference from published scripts
require('./vendor/livewire-ui/modal');


// Reference from vendor
require('../../vendor/livewire-ui/modal/resources/js/modal');
window.Alpine = Alpine;

Alpine.start();
