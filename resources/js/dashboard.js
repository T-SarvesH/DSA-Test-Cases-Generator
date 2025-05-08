// resources/js/dashboard.js

import Chart from 'chart.js/auto';

console.log("dashboard.js file started executing");

document.addEventListener('DOMContentLoaded', function () {
    console.log("DOMContentLoaded fired in dashboard.js");

    // --- Code for Solved Problems Doughnut Chart ---
    const solvedCtx = document.getElementById('solvedProblemsChart');
    const solvedChartData = window.solvedChartData;
    const totalSolved = window.totalSolved;

    console.log("Value of window.solvedChartData inside DOMContentLoaded:", solvedChartData);
    console.log("Value of window.totalSolved inside DOMContentLoaded:", totalSolved);

    // Custom Chart.js Plugin for Center Text (No changes needed here, color is black)
    const centerTextPlugin = {
        id: 'centerText',
        beforeDraw(chart) {
            // Only apply this plugin to doughnut charts
            if (chart.config.type === 'doughnut') {
                const { ctx, chartArea: { top, bottom, left, right, width, height } } = chart;
                ctx.save();
                ctx.font = 'bold 30px sans-serif'; // Ensure font is readable
                ctx.fillStyle = '#333'; // Darker text color for better contrast
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                const centerX = (left + right) / 2;
                const centerY = (top + bottom) / 2;
                ctx.fillText(totalSolved, centerX, centerY);
                ctx.restore();
            }
        }
    };


    if (solvedCtx && typeof solvedChartData !== 'undefined') {
        console.log("Conditions met (solved canvas and data found), attempting to create doughnut chart...");
        new Chart(solvedCtx, {
            type: 'doughnut',
            data: solvedChartData,
            options: {
                 plugins: {
                     legend: {
                         display: true,
                         position: 'top',
                         // Customize legend text color if needed
                         labels: {
                             color: '#555', // Darker legend text
                         }
                     },
                     title: {
                         display: false, // Title is handled by h2 in HTML
                         text: 'Problems Solved by Difficulty'
                     },
                     tooltip: { // Ensure tooltips are clean
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255,255,255,0.5)',
                        borderWidth: 1,
                        cornerRadius: 4,
                     }
                 },
                 responsive: true,
                 maintainAspectRatio: false,
                 aspectRatio: 1, // Ensures it tries to be square within container
                 cutout: '60%',
                 // Add animation if default is not smooth
                 animation: {
                    animateRotate: true,
                    animateScale: true
                 },
            },
            plugins: [centerTextPlugin] // Register the custom plugin
        });
        console.log("Doughnut chart creation code executed.");
    } else {
        if (!solvedCtx) {
            console.error("Solved Problems Canvas element #solvedProblemsChart not found!");
        }
        if (typeof solvedChartData === 'undefined') {
            console.error("JavaScript variable 'solvedChartData' not found on window! Doughnut chart data not passed from Blade.");
        }
    }

    // --- Code for Most Practiced Topics Histogram Chart ---
    const topicsCtx = document.getElementById('topicsChart'); // Get the new canvas
    const topicsChartData = window.topicChartData; // Get data for histogram

     console.log("Value of window.topicChartData inside DOMContentLoaded:", topicsChartData);

    if (topicsCtx && typeof topicsChartData !== 'undefined') {
        console.log("Conditions met (topics canvas and data found), attempting to create histogram chart...");
        new Chart(topicsCtx, {
            type: 'bar', // Bar chart type for histogram
            data: topicsChartData, // Use the histogram data
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                         title: { // Add title to Y-axis
                            display: true,
                            text: 'Number of Problems',
                            color: '#555' // Title text color
                        },
                        ticks: {
                            color: '#555' // Axis label text color
                        },
                        grid: { // Make grid lines lighter
                            color: 'rgba(0, 0, 0, 0.08)'
                        }
                    },
                    x: { // Add title to X-axis
                         title: {
                             display: true,
                             text: 'Topic',
                             color: '#555' // Title text color
                         },
                         ticks: {
                             color: '#555', // Axis label text color
                             autoSkip: false, // Prevent skipping labels if needed
                             maxRotation: 45, // Rotate labels if they overlap
                             minRotation: 0
                         },
                         grid: { // Make grid lines lighter
                             color: 'rgba(0, 0, 0, 0.08)'
                         }
                    }
                },
                 plugins: {
                     legend: {
                         display: false, // Hide legend for a single bar series
                     },
                     title: {
                         display: false, // Title is in h2 above
                         text: 'Most Practiced Topics'
                     },
                      tooltip: { // Ensure tooltips are clean
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255,255,255,0.5)',
                        borderWidth: 1,
                        cornerRadius: 4,
                     }
                 },
                 responsive: true,
                 maintainAspectRatio: false,
                 // Add animation if default is not smooth
                 animation: {
                     duration: 800, // milliseconds
                     easing: 'easeOutQuart'
                 }
            }
        });
        console.log("Histogram chart creation code executed.");
    } else {
        if (!topicsCtx) {
            console.error("Topics Canvas element #topicsChart not found!");
        }
        if (typeof topicsChartData === 'undefined') {
            console.error("JavaScript variable 'topicChartData' not found on window! Histogram chart data not passed from Blade.");
        }
    }
});