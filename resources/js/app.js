import './bootstrap';

import Alpine from 'alpinejs';
import paneEditor from './pane-editor';

window.Alpine = Alpine;

Alpine.data('paneEditor', paneEditor);

Alpine.start();
