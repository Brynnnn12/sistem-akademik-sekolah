@props([
    'data' => [],
    'title' => 'Progress Chart',
    'color' => 'rgba(59, 130, 246, 1)',
])

<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $title }}</h3>

    <div class="chart-container">
        <canvas id="progressChart{{ uniqid() }}" width="100%" height="300"></canvas>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartId = 'progressChart{{ uniqid() }}';
        const ctx = document.getElementById(chartId);

        if (ctx && window.Chart) {
            const chartData = @json($data);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels || ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Progress',
                        data: chartData.values || [12, 19, 3, 5, 2, 3, 9],
                        borderColor: '{{ $color }}',
                        backgroundColor: '{{ $color }}20',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '{{ $color }}',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }
    });
</script>
