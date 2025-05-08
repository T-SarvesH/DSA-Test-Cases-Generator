// resources/js/dashboard.js

import Chart from 'chart.js/auto';

console.log("dashboard.js file started executing");

document.addEventListener('DOMContentLoaded', function () {
    console.log("DOMContentLoaded fired in dashboard.js");

    const ctx = document.getElementById('solvedProblemsChart');

    const chartData = window.solvedChartData;
    const totalSolved = window.totalSolved;

    console.log("Value of window.solvedChartData inside DOMContentLoaded:", chartData);
    console.log("Value of window.totalSolved inside DOMContentLoaded:", totalSolved);


    // Custom Chart.js Plugin for Center Text (No changes needed here)
    const centerTextPlugin = {
        id: 'centerText',
        beforeDraw(chart) {
            const { ctx, chartArea: { top, bottom, left, right, width, height } } = chart;
            ctx.save();
            ctx.font = 'bold 30px sans-serif';
            ctx.fillStyle = '#000';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            const centerX = (left + right) / 2;
            const centerY = (top + bottom) / 2;
            ctx.fillText(totalSolved, centerX, centerY);
            ctx.restore();
        }
    };

    if (ctx && typeof chartData !== 'undefined') {
        console.log("Conditions met (canvas and data found), attempting to create chart...");
        new Chart(ctx, {
            type: 'doughnut', // <-- Changed from 'pie' to 'doughnut'
            data: chartData,
            options: {
                 plugins: {
                     legend: {
                         display: true,
                         position: 'top', // Optional: place legend at the top
                     },
                     title: {
                         display: false,
                         text: 'Problems Solved by Difficulty'
                     },
                     // Enable the custom plugin
                     centerText: true
                 },
                 responsive: true,
                 maintainAspectRatio: false,
                 aspectRatio: 1, // Keep square aspect ratio

                 // --- Doughnut Specific Option ---
                 cutout: '60%', // <-- Add this to create the hole (e.g., 60% of the radius)
                 // -------------------------------

            },
            plugins: [centerTextPlugin] // Register the custom plugin
        });
        console.log("Chart creation code executed.");
    } else {
        if (!ctx) {
            console.error("Canvas element #solvedProblemsChart not found!");
        }
        if (typeof chartData === 'undefined') {
            console.error("JavaScript variable 'solvedChartData' not found on window! Chart data not passed from Blade.");
        }
    }
});