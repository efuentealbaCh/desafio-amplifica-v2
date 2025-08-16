<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium mb-4">Métricas de la Tienda</h3>
                    <form action="{{ route('dashboard') }}" method="GET" class="mb-6 flex items-end space-x-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Desde:</label>
                            <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Hasta:</label>
                            <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 transition ease-in-out duration-150">
                                Filtrar
                            </button>
                        </div>
                    </form>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="p-4 bg-gray-50 rounded-lg shadow">
                            <h4 class="font-semibold text-center mb-2">Productos Comprados por Mes</h4>
                            <canvas id="monthlySalesChart"></canvas>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-lg shadow">
                            <h4 class="font-semibold text-center mb-2">Productos Más Vendidos</h4>
                            <canvas id="topSellingChart"></canvas>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-lg shadow">
                            <h4 class="font-semibold text-center mb-2">Cantidad de Productos Disponibles</h4>
                            <p class="text-sm text-center text-gray-500 mb-2">(Inventario actual)</p>
                            <div class="flex justify-center">
                                <canvas id="availableProductsChart" class="max-w-xs"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const monthlySalesData = @json($monthlySalesData);
        const topSellingProductsData = @json($topSellingProductsData);
        const availableProductsData = @json($availableProductsData);

        new Chart(document.getElementById('monthlySalesChart'), {
            type: 'bar',
            data: {
                labels: monthlySalesData.labels,
                datasets: [{
                    label: 'Cantidad de Productos',
                    data: monthlySalesData.data,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad'
                        }
                    }
                }
            }
        });
        new Chart(document.getElementById('topSellingChart'), {
            type: 'bar',
            data: {
                labels: topSellingProductsData.labels,
                datasets: [{
                    label: 'Cantidad Vendida',
                    data: topSellingProductsData.data,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad'
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('availableProductsChart'), {
            type: 'doughnut',
            data: {
                labels: availableProductsData.labels,
                datasets: [{
                    label: 'Cantidad en Inventario',
                    data: availableProductsData.data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += context.parsed + ' unidades';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
