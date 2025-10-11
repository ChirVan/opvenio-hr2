<x-app-layout>
	@section('navbar')
		@include('layouts.navbar')
	@endsection

	@section('sidebar')
		@include('layouts.sidebar')
	@endsection

	<div class="py-6 px-4 print-area">
		
			<div class="bg-gradient-to-r from-green-600 to-green-800 rounded-t-lg shadow-lg p-6 text-white">
				<div class="flex items-center justify-between">
					<div>
						<h1 class="text-3xl font-bold mb-2">Successors List</h1>
						<p class="text-green-100">Promotion Records Overview</p>
					</div>
				</div>
			</div>
			<div class="bg-white rounded-b-lg shadow-lg">
				<div class="p-6">
					@php
						$promotions = \App\Modules\succession_planning\Models\Promotion::all();
					@endphp
					<div class="flex justify-end mb-4">
						<button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold text-xs shadow transition flex items-center gap-2">
							<i class='bx bx-printer text-white'></i> Export / Print
						</button>
					</div>
					<div class="overflow-x-auto">
						<table class="min-w-full divide-y divide-gray-200 shadow-sm">
							<thead class="bg-gray-50">
								<tr>
									<th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Employee ID</th>
									<th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Employee Name</th>
									<th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Email</th>
									<th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Current Job</th>
									<th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Potential Job</th>
								
									<th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>

								</tr>
							</thead>
							<tbody class="bg-white divide-y divide-gray-100">
								@forelse ($promotions as $promotion)
									<tr class="hover:bg-green-50 transition">
										<td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-gray-700">{{ $promotion->employee_id }}</td>
										<td class="px-6 py-4 whitespace-nowrap text-gray-900 font-semibold">{{ $promotion->employee_name }}</td>
										<td class="px-6 py-4 whitespace-nowrap text-gray-700"><span class="inline-flex items-center gap-2"><i class='bx bx-envelope text-green-600'></i>{{ $promotion->employee_email }}</span></td>
										<td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $promotion->job_title }}</td>
										<td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $promotion->potential_job }}</td>
									
										<td class="px-6 py-4 whitespace-nowrap">
											<span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $promotion->status == 'approved' ? 'bg-green-200 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
												{{ ucfirst($promotion->status) }}
											</span>
										</td>

									</tr>
								@empty
									<tr>
										<td colspan="8" class="px-6 py-4 text-center text-gray-500">No promotions found.</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
		
	</div>

	<style>
		@media print {
			.print-area {
				width: 100vw !important;
				max-width: 100vw !important;
				margin: 0 !important;
				padding: 0 !important;
				box-shadow: none !important;
				background: white !important;
			}
			.print-area table {
				width: 100vw !important;
				max-width: 100vw !important;
				font-size: 0.9em;
			}
			.print-area th, .print-area td {
				white-space: normal !important;
				word-break: break-word !important;
				padding: 8px !important;
			}
		}
		@media print {
			.print-area .overflow-x-auto {
				overflow: visible !important;
			}
			.print-area table {
				width: 100% !important;
				font-size: 0.9em;
			}
		}
		@media print {
			body * {
				visibility: hidden !important;
			}
			.print-area, .print-area * {
				visibility: visible !important;
			}
			.print-area {
				position: absolute;
				left: 0;
				top: 0;
				width: 100vw;
				background: white;
				z-index: 9999;
			}
			.print-area .flex.justify-end,
			.print-area .alert,
			.print-area .export-btn {
				display: none !important;
			}
		}
		/* Enhanced table styling for clarity and modern look */
		.table-successors th {
			background: linear-gradient(90deg, #e0f7fa 0%, #e8f5e9 100%);
			color: #256029;
			font-weight: 700;
			letter-spacing: 0.05em;
		}
		.table-successors td {
			border-bottom: 1px solid #e5e7eb;
		}
		.table-successors tr:hover {
			background-color: #e6ffe6;
		}
		.table-successors .badge {
			padding: 0.4em 0.8em;
			border-radius: 999px;
			font-size: 0.85em;
			font-weight: 600;
		}
		.table-successors .badge-approved {
			background: #bbf7d0;
			color: #166534;
		}
		.table-successors .badge-pending {
			background: #fef9c3;
			color: #92400e;
		}
	</style>
</x-app-layout>
@php
	$promotions = \App\Modules\succession_planning\Models\Promotion::all();
@endphp

<div class="container mt-4">
	<h2>Successors (Promotions)</h2>
	<table class="table table-bordered table-striped mt-3">
		<thead>
			<tr>
				<th>ID</th>
				<th>Employee Name</th>
				<th>Email</th>
				<th>Current Job</th>
				<th>Potential Job</th>
				<th>Score</th>
				<th>Category</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			@forelse ($promotions as $promotion)
				<tr>
					<td>{{ $promotion->id }}</td>
					<td>{{ $promotion->employee_name }}</td>
					<td>{{ $promotion->employee_email }}</td>
					<td>{{ $promotion->job_title }}</td>
					<td>{{ $promotion->potential_job }}</td>
					<td>{{ $promotion->assessment_score }}</td>
					<td>{{ $promotion->category }}</td>
					<td>{{ $promotion->status }}</td>
				</tr>
			@empty
				<tr>
					<td colspan="8" class="text-center">No promotions found.</td>
				</tr>
			@endforelse
		</tbody>
	</table>
</div>
