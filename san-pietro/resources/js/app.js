import './bootstrap';

import Alpine from 'alpinejs';
import { initializeWeeklyRecordCalculations } from './weekly-record-calculations';

window.Alpine = Alpine;

Alpine.start();

// Inizializza calcoli Weekly Records
initializeWeeklyRecordCalculations();
