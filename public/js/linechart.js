document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('caseChart').getContext('2d');

    // Blue gradient for the "Total" line
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(54, 162, 235, 0.5)');
    gradient.addColorStop(1, 'rgba(54, 162, 235, 0.1)');

    // Only the "Total" line
    const datasets = [{
        label: 'Total Cases',
        data: Object.values(caseTypeData['Total']),
        borderColor: 'rgba(54, 162, 235, 1)',
        backgroundColor: gradient,
        fill: true,
        tension: 0.4,
        pointBackgroundColor: '#ffffff',
        pointBorderColor: 'rgba(54, 162, 235, 1)',
        pointRadius: 5,
        pointBorderWidth: 2,
        borderWidth: 3,
        order: 1
    }];

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ],
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true },
                tooltip: {
                    backgroundColor: '#222',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#555',
                    borderWidth: 1,
                    cornerRadius: 6,
                    padding: 10
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#555', font: { size: 12 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(200, 200, 200, 0.2)', borderDash: [5, 5] },
                    ticks: { color: '#555', font: { size: 12 } }
                }
            }
        }
    });
});
