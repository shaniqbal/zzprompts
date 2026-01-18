/**
 * Archive Classic - Grid/List View Toggle
 * 
 * @package zzprompts
 * @version 1.0.0
 */

(function () {
    'use strict';

    // Wait for DOM to load
    document.addEventListener('DOMContentLoaded', function () {

        const gridViewBtn = document.getElementById('gridViewBtn');
        const listViewBtn = document.getElementById('listViewBtn');
        const promptGrid = document.querySelector('.classic-prompts-grid');

        if (!gridViewBtn || !listViewBtn || !promptGrid) {
            return; // Exit if elements not found
        }

        // Grid View Click
        gridViewBtn.addEventListener('click', function () {
            // Toggle active states
            gridViewBtn.classList.add('active');
            listViewBtn.classList.remove('active');

            // Switch to grid layout
            promptGrid.classList.remove('list-view');
            promptGrid.classList.add('grid-view');

            // Save preference
            localStorage.setItem('zzprompts_archive_view', 'grid');
        });

        // List View Click
        listViewBtn.addEventListener('click', function () {
            // Toggle active states
            listViewBtn.classList.add('active');
            gridViewBtn.classList.remove('active');

            // Switch to list layout
            promptGrid.classList.remove('grid-view');
            promptGrid.classList.add('list-view');

            // Save preference
            localStorage.setItem('zzprompts_archive_view', 'list');
        });

        // Load saved preference
        const savedView = localStorage.getItem('zzprompts_archive_view');
        if (savedView === 'list') {
            listViewBtn.click();
        } else {
            // Default to grid
            promptGrid.classList.add('grid-view');
        }

    });

})();
