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

						// Enrich job titles from HR4 API for records with missing/placeholder titles
						$needsJobTitle = $promotions->filter(fn($p) => empty($p->job_title) || $p->job_title === 'Not Specified' || $p->job_title === '-');
						if ($needsJobTitle->isNotEmpty()) {
							try {
								$employeeApiService = app(\App\Services\EmployeeApiService::class);
								$allEmployees = $employeeApiService->getEmployees();
								if ($allEmployees) {
									$employeeMap = collect($allEmployees)->keyBy('employee_id');
									foreach ($needsJobTitle as $promotion) {
										$emp = $employeeMap[$promotion->employee_id] ?? null;
										if ($emp) {
											$jobTitle = $emp['job_title'] ?? $emp['job']['job_title'] ?? null;
											if ($jobTitle) {
												$promotion->job_title = $jobTitle;
												\Illuminate\Support\Facades\DB::connection('succession_planning')
													->table('promotions')
													->where('id', $promotion->id)
													->update(['job_title' => $jobTitle, 'updated_at' => now()]);
											}
										}
									}
								}
							} catch (\Exception $e) {
								// Silently continue if API fails
							}
						}
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
									{{-- <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Email</th> --}}
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
										{{-- <td class="px-6 py-4 whitespace-nowrap text-gray-700"><span class="inline-flex items-center gap-2"><i class='bx bx-envelope text-green-600'></i>{{ $promotion->employee_email }}</span></td> --}}
										<td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $promotion->job_title }}</td>
										<td class="px-6 py-4 whitespace-nowrap text-gray-700 font-semibold text-green-700">{{ $promotion->potential_job }}</td>
										<td class="px-6 py-4 whitespace-nowrap">
											@if($promotion->status == 'promoted')
												<span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-blue-200 text-blue-800">
													<i class='bx bx-check-double mr-1'></i>Promoted
												</span>
											@elseif($promotion->status == 'approved')
												<span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-green-200 text-green-800">
													<i class='bx bx-check mr-1'></i>Approved
												</span>
											@else
												<span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">
													{{ ucfirst($promotion->status) }}
												</span>
											@endif
										</td>

									</tr>
								@empty
									<tr>
										<td colspan="5" class="px-6 py-4 text-center text-gray-500">No promotions found.</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
		
	</div>

	<!-- SweetAlert2 CDN -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	
	<script>
		function executePromotion(promotionId, employeeName, newJobTitle) {
			Swal.fire({
				title: 'Execute Promotion',
				html: `
					<div class="text-left">
						<p class="text-gray-600 mb-4">Are you sure you want to promote <strong>${employeeName}</strong> to <strong>${newJobTitle}</strong>?</p>
						<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
							<p class="text-sm text-yellow-700">
								<i class="bx bx-info-circle mr-1"></i>
								This action will update the employee's job title in the HR system and cannot be undone.
							</p>
						</div>
						<div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
							<p class="text-sm text-blue-700">
								<strong>New Position:</strong> ${newJobTitle}
							</p>
						</div>
					</div>
				`,
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: '<i class="bx bx-rocket mr-1"></i> Execute Promotion',
				confirmButtonColor: '#7C3AED',
				cancelButtonText: 'Cancel',
				showLoaderOnConfirm: true,
				preConfirm: async () => {
					try {
						const response = await fetch(`/promotion/${promotionId}/execute`, {
							method: 'POST',
							headers: {
								'Content-Type': 'application/json',
								'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
								'Accept': 'application/json'
							}
						});
						
						const data = await response.json();
						
						if (!data.success) {
							throw new Error(data.message || 'Failed to execute promotion');
						}
						
						return data;
					} catch (error) {
						Swal.showValidationMessage(`Error: ${error.message}`);
					}
				},
				allowOutsideClick: () => !Swal.isLoading()
			}).then((result) => {
				if (result.isConfirmed && result.value) {
					Swal.fire({
						title: 'Promotion Successful!',
						html: `
							<div class="text-left">
								<p class="text-gray-600 mb-3">${result.value.message}</p>
								<div class="bg-green-50 border border-green-200 rounded-lg p-4">
									<div class="flex items-center mb-2">
										<i class="bx bx-check-circle text-green-500 text-2xl mr-2"></i>
										<span class="font-semibold text-green-800">Job Title Updated</span>
									</div>
									<p class="text-sm text-green-700">
										<strong>${result.value.data.employee_name}</strong> has been promoted from 
										<span class="line-through text-gray-500">${result.value.data.old_job_title}</span> 
										to <strong class="text-green-600">${result.value.data.new_job_title}</strong>
									</p>
								</div>
							</div>
						`,
						icon: 'success',
						confirmButtonText: 'Great!',
						confirmButtonColor: '#10B981'
					}).then(() => {
						// Reload page to reflect changes
						location.reload();
					});
				}
			});
		}
	</script>

	<style>
		.no-print {
			/* Action column visible normally */
		}
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
			.no-print {
				display: none !important;
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
			.print-area .export-btn,
			.print-area .no-print {
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
