# Chart Components

Komponen chart menggunakan Chart.js untuk membuat berbagai jenis grafik di aplikasi Laravel.

## Komponen yang Tersedia

### 1. `x-chart.chart` - Komponen Chart Umum

```blade
<x-chart.chart
    type="line"
    :data="[
        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
        'datasets' => [[
            'label' => 'Sales',
            'data' => [65, 59, 80, 81, 56],
            'borderColor' => 'rgba(75, 192, 192, 1)',
            'backgroundColor' => 'rgba(75, 192, 192, 0.2)'
        ]]
    ]"
    :options="[
        'responsive' => true,
        'scales' => [
            'y' => ['beginAtZero' => true]
        ]
    ]"
/>
```

### 2. `x-chart.todo-stats` - Chart Statistik Todo

```blade
<x-chart.todo-stats
    :completed="15"
    :pending="8"
    :total="23"
/>
```

### 3. `x-chart.progress-chart` - Chart Progress Line

```blade
<x-chart.progress-chart
    title="Weekly Progress"
    :data="[
        'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        'values' => [12, 19, 3, 5, 2, 3, 9]
    ]"
    color="rgba(59, 130, 246, 1)"
/>
```

### 4. `x-chart.bar-chart` - Chart Bar

```blade
<x-chart.bar-chart
    title="Monthly Statistics"
    :data="[
        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        'values' => [65, 59, 80, 81, 56, 55]
    ]"
/>
```

## Tipe Chart yang Didukung

-   `line` - Line chart
-   `bar` - Bar chart
-   `doughnut` - Doughnut chart
-   `pie` - Pie chart
-   `radar` - Radar chart
-   `polarArea` - Polar area chart

## Penggunaan di Controller

```php
public function dashboard()
{
    $completedTodos = Todo::where('completed', true)->count();
    $pendingTodos = Todo::where('completed', false)->count();

    return view('dashboard', compact('completedTodos', 'pendingTodos'));
}
```

## Penggunaan di View

```blade
<x-layout.dashboard>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-chart.todo-stats :completed="$completedTodos" :pending="$pendingTodos" :total="$completedTodos + $pendingTodos" />

        <x-chart.progress-chart
            title="Weekly Activity"
            :data="[
                'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                'values' => [5, 12, 8, 15, 7, 10, 9]
            ]"
        />
    </div>
</x-layout.dashboard>
```
