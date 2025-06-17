document.addEventListener("DOMContentLoaded", function () {
    const pieCtx = document.getElementById('caseTypePie').getContext('2d');
    const total = pieData.reduce((a, b) => a + b, 0);

    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: pieLabels,
            datasets: [{
                data: pieData,
                backgroundColor: pieColors,
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 1200,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: { display: false }, // Hide default legend
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const percent = total ? ((value / total) * 100).toFixed(1) : 0;
                            return `${label}: ${value} (${percent}%)`;
                        }
                    },
                    backgroundColor: '#222',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#3c91e6',
                    borderWidth: 1,
                    padding: 12,
                    caretSize: 7,
                    cornerRadius: 8
                },
                datalabels: {
                    color: '#fff',
                    formatter: (value, context) => {
                        const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return percentage > 5 ? percentage + '%' : ''; // Only show if >5%
                    },
                    font: {
                        weight: 'bold',
                        size: 15
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    // Modern custom legend
    const legendContainer = document.getElementById('caseTypeLegend');
    if (legendContainer) {
        legendContainer.innerHTML = pieLabels.map((label, i) => {
            const percent = total ? ((pieData[i] / total) * 100).toFixed(1) : 0;
            const value = pieData[i];
            return `<div class="pie-legend-item">
                <span class="pie-legend-color" style="background:${pieColors[i]};"></span>
                <span class="pie-legend-label">${label}</span>
                <span class="pie-legend-percent">${percent}%</span>
                <span class="pie-legend-value">${value}</span>
            </div>`;
        }).join('');
    }
});
