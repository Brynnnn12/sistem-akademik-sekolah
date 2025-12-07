@props([
    'data' => [],
    'title' => 'Bar Chart',
    'colors' => [
        'rgba(59, 130, 246, 0.8)',
        'rgba(16, 185, 129, 0.8)',
        'rgba(245, 158, 11, 0.8)',
        'rgba(239, 68, 68, 0.8)',
    ],
])

<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $title }}</h3>

    <div class="chart-container">
        <canvas id="barChart{{ uniqid() }}" width="100%" height="300"></canvas>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartId = 'barChart{{ uniqid() }}';
        const ctx = document.getElementById(chartId);

        if (ctx && window.Chart) {
            const chartData = @json($data);
            const colors = @json($colors);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Value',
                        data: chartData.values || [65, 59, 80, 81, 56, 55],
                        backgroundColor: colors,
                        borderColor: colors.map(color => color.replace('0.8', '1')),
                        borderWidth: 1,
                        borderRadius: 4,
                        borderSkipped: false
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
                    }
                }
            });
        }
    });
</script>
