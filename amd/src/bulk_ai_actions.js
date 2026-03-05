// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * AMD module for bulk AI tools enable/disable actions.
 *
 * @module      local_coursesettings/bulk_ai_actions
 * @copyright   2026
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import ModalFactory from 'core/modal_factory';
import ModalEvents from 'core/modal_events';
import {get_string as getString} from 'core/str';

/**
 * Initialise the bulk AI action buttons.
 *
 * @param {string} actionUrl The URL of the bulk action handler.
 * @param {string} sesskey   The Moodle session key.
 */
export const init = (actionUrl, sesskey) => {
    const disableBtn = document.getElementById('local-coursesettings-disable-ai');
    const enableBtn = document.getElementById('local-coursesettings-enable-ai');

    if (disableBtn) {
        disableBtn.addEventListener('click', (e) => {
            e.preventDefault();
            showConfirmModal(
                getString('disableaitools_all', 'local_coursesettings'),
                getString('disableaitools_all_confirm', 'local_coursesettings'),
                actionUrl,
                sesskey,
                0
            );
        });
    }

    if (enableBtn) {
        enableBtn.addEventListener('click', (e) => {
            e.preventDefault();
            showConfirmModal(
                getString('enableaitools_all', 'local_coursesettings'),
                getString('enableaitools_all_confirm', 'local_coursesettings'),
                actionUrl,
                sesskey,
                1
            );
        });
    }
};

/**
 * Show a confirmation modal, then submit the action if confirmed.
 *
 * @param {Promise} titlePromise  Promise resolving to the modal title string.
 * @param {Promise} bodyPromise   Promise resolving to the modal body string.
 * @param {string}  actionUrl     The URL to POST to on confirmation.
 * @param {string}  sesskey       The Moodle session key.
 * @param {number}  value         0 to disable, 1 to enable.
 */
const showConfirmModal = async(titlePromise, bodyPromise, actionUrl, sesskey, value) => {
    const [title, body] = await Promise.all([titlePromise, bodyPromise]);

    const modal = await ModalFactory.create({
        type: ModalFactory.types.SAVE_CANCEL,
        title: title,
        body: body,
    });

    modal.setSaveButtonText(await getString('confirm', 'core'));

    modal.getRoot().on(ModalEvents.save, () => {
        const form = document.createElement('form');
        form.method = 'post';
        form.action = actionUrl;

        const sesskeyInput = document.createElement('input');
        sesskeyInput.type = 'hidden';
        sesskeyInput.name = 'sesskey';
        sesskeyInput.value = sesskey;

        const valueInput = document.createElement('input');
        valueInput.type = 'hidden';
        valueInput.name = 'enableaitools';
        valueInput.value = value;

        const confirmInput = document.createElement('input');
        confirmInput.type = 'hidden';
        confirmInput.name = 'confirmed';
        confirmInput.value = '1';

        form.appendChild(sesskeyInput);
        form.appendChild(valueInput);
        form.appendChild(confirmInput);
        document.body.appendChild(form);
        form.submit();
    });

    modal.show();
};

