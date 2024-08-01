<div>
    @if ($charts)
        @forelse ($charts as $chart)
            <x-card class="max-w-3xl mx-auto mt-2 relative">
                <div class="mb-4">
                    <div class="flex items-start">
                        <!-- User Avatar -->
                        <img class="w-12 h-12 rounded-full mr-4" src="{{ $chart->user->profile_photo_url }}"
                            alt="{{ $chart->user->fullName }}">
                        <div>
                            <!-- User Name and Time -->
                            <div class="text-lg font-semibold text-gray-800">{{ $chart->user->fullName }}</div>
                            <div class="text-sm text-gray-500">{{ $chart->created_at_human }}</div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <!-- chart Description -->
                    <div class="text-gray-600 mt-2">{{ $chart->description }}</div>
                    @php
                        $data = json_decode($chart->data, true); // Decodifica el JSON en un array
                    @endphp
                    @if ($chart->chart_type == 1)
                        <!-- General charts are viewed by all roles -->
                        @php
                            $encodeData = json_encode($data);
                        @endphp
                        <x-chart id="{{ $chart->code }}" :title="$chart->title" :charttype="$chart->chart_type" :type="$chart->type"
                            :data="$encodeData" :showlabels="$chart->showlabels" :showlegend="$chart->showlegend" />
                    @else
                        @if (Auth::user()->role_id == 4)
                            <!-- Partner -->
                            @php
                                $data = json_decode($chart->data, true); // Decodifica el JSON en un array
                                if (isset($data[Auth::id()])) {
                                    $encodeData = json_encode($data[Auth::id()]);
                                }
                            @endphp
                            @if (isset($data[Auth::id()]))
                                <x-chart id="{{ $chart->code . '-' . Auth::id() }}" :title="$chart->title" :charttype="$chart->chart_type"
                                    :type="$chart->type" :data="$encodeData" :showlabels="$chart->showlabels" :showlegend="$chart->showlegend" />
                                    @else
                                    <x-label>No hay data para este usuario</x-label>
                            @endif
                        @elseif (Auth::user()->role_id == 3)
                            <!-- Manager -->
                            @php
                                $data = json_decode($chart->data, true);
                            @endphp
                            @foreach (Auth::user()->partners as $partner)
                                @if (isset($data[$partner->id]))
                                    @php
                                        $encodeData = json_encode($data[$partner->id]);
                                    @endphp
                                    <x-chart id="{{ $chart->code . '-' . $partner->id }}" :title="$chart->title"
                                        :charttype="$chart->chart_type" :type="$chart->type" :data="$encodeData" :showlabels="$chart->showlabels"
                                        :showlegend="$chart->showlegend" />
                                @endif
                            @endforeach
                        @elseif (in_array(Auth::user()->role_id, [1, 2]))
                            <!-- Super Admin or Admin -->
                            @php
                                $data = json_decode($chart->data, true);
                            @endphp
                            @foreach ($data as $userId => $chartData)
                                @php
                                    $encodeData = json_encode($chartData);
                                @endphp
                                <x-chart id="{{ $chart->code . '-' . $userId }}" :title="$chart->title" :charttype="$chart->chart_type"
                                    :type="$chart->type" :data="$encodeData" :showlabels="$chart->showlabels" :showlegend="$chart->showlegend" />
                            @endforeach
                        @endif
                    @endif

                </div>

            </x-card>
        @empty
            <x-card>
                <x-label class="mt-10 text-center">Aún no se han publicado gráficos</x-label>
            </x-card>
        @endforelse
    @else
        <x-card>
            <x-label class="mt-10 text-center">Aún no se han publicado gráficos</x-label>
        </x-card>
    @endif
</div>
