@extends('layouts.app')
@section('title', 'Statistiques - EasyColoc')
@section('breadcrumb', 'Statistiques')
@section('content')
    <div class="space-y-6">
        <h1 class="text-2xl font-bold text-gray-900">Statistiques {{ now()->year }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Dépenses par mois (DH)</h2>
                <canvas id="expensesChart"></canvas>
            </div>
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Inscriptions par mois</h2>
                <canvas id="usersChart"></canvas>
            </div>
            <div class="bg-white rounded-lg border border-gray-200 p-6 md:col-span-2">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Colocations créées par mois</h2>
                <canvas id="colocationsChart"></canvas>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('expensesChart'), {
            type: 'bar',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'Montant (DH)',
                    data: @json($expensesData),
                    backgroundColor: 'rgba(99, 102, 241, 0.5)',
                    borderColor: 'rgb(99, 102, 241)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        new Chart(document.getElementById('usersChart'), {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'Inscriptions',
                    data: @json($usersData),
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        new Chart(document.getElementById('colocationsChart'), {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'Colocations',
                    data: @json($colocationsData),
                    borderColor: 'rgb(245, 158, 11)',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endsection
