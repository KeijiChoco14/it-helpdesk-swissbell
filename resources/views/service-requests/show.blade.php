<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-3">
                <a href="{{ route('service-requests.index') }}" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ $serviceRequest->ticket_number }}</h1>
                </div>
            </div>
            <button onclick="window.print()" class="btn btn-secondary text-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0v2.796c0 .12.048.235.134.32l2.36 2.36c.085.085.2.134.32.134h5.372c.12 0 .235-.048.32-.134l2.36-2.36a.453.453 0 00.134-.32V7.07z"/></svg>
                Print PDF
            </button>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Ticket Header -->
            <div class="glass-card p-6">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-5">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 leading-tight">{{ $serviceRequest->title }}</h2>
                        <div class="flex items-center gap-2 mt-2 text-sm text-slate-500">
                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-red-400 to-purple-500 flex items-center justify-center text-[10px] font-bold text-white">{{ strtoupper(substr($serviceRequest->user->name, 0, 1)) }}</div>
                            <span>{{ $serviceRequest->user->name }}</span>
                            <span class="text-slate-300">·</span>
                            <span>{{ $serviceRequest->department->name ?? 'N/A' }}</span>
                            <span class="text-slate-300">·</span>
                            <span>{{ $serviceRequest->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                    <span class="badge {{ $serviceRequest->status === 'OPEN' ? 'badge-open' : ($serviceRequest->status === 'IN_PROGRESS' ? 'badge-in-progress' : ($serviceRequest->status === 'RESOLVED' ? 'badge-resolved' : 'badge-closed')) }}">
                        <span class="w-2 h-2 rounded-full {{ $serviceRequest->status === 'OPEN' ? 'bg-red-500' : ($serviceRequest->status === 'IN_PROGRESS' ? 'bg-amber-500' : ($serviceRequest->status === 'RESOLVED' ? 'bg-emerald-500' : 'bg-slate-400')) }}"></span>
                        {{ $serviceRequest->status }}
                    </span>
                </div>
                <div class="prose prose-slate max-w-none text-sm leading-relaxed bg-slate-50 rounded-xl p-5 border border-slate-100">
                    {{ $serviceRequest->description }}
                </div>
            </div>
            </div>

            <!-- Rating Section -->
            @if($serviceRequest->status === 'CLOSED' && $serviceRequest->user_id === auth()->id() && !$serviceRequest->rating)
                <div class="glass-card p-6 border-2 border-red-100 bg-red-50/20">
                    <h3 class="text-base font-bold text-slate-800 mb-2">Rate Our Service</h3>
                    <p class="text-sm text-slate-500 mb-4">How satisfied are you with the resolution of this ticket?</p>
                    <form action="{{ route('service-requests.rate', $serviceRequest) }}" method="POST">
                        @csrf
                        <div class="flex items-center gap-2 mb-4" x-data="{ rating: 0, hover: 0 }">
                            <input type="hidden" name="rating" x-model="rating">
                            <template x-for="i in 5">
                                <button type="button" 
                                    @click="rating = i" 
                                    @mouseenter="hover = i" 
                                    @mouseleave="hover = 0"
                                    class="focus:outline-none transition-colors"
                                    :class="i <= (hover || rating) ? 'text-amber-400' : 'text-slate-300'">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </button>
                            </template>
                        </div>
                        <textarea name="feedback" rows="2" class="form-input text-sm mb-4" placeholder="Optional feedback..."></textarea>
                        <button type="submit" class="btn btn-primary text-sm w-full sm:w-auto">Submit Feedback</button>
                    </form>
                </div>
            @elseif($serviceRequest->rating)
                <div class="glass-card p-6">
                    <h3 class="text-sm font-bold text-slate-800 mb-2">Service Rating</h3>
                    <div class="flex items-center gap-1 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $serviceRequest->rating ? 'text-amber-400' : 'text-slate-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    @if($serviceRequest->feedback)
                        <p class="text-sm text-slate-600 italic bg-slate-50 p-3 rounded-lg border border-slate-100">"{{ $serviceRequest->feedback }}"</p>
                    @endif
                </div>
            @endif

            <!-- Comments -->
            <div class="glass-card" x-data="{
                poll() {
                    setInterval(async () => {
                        try {
                            let res = await fetch('{{ route('service-requests.comments.index', $serviceRequest) }}');
                            let data = await res.json();
                            let container = this.$refs.commentsContainer;
                            let isScrolledToBottom = container.scrollHeight - container.clientHeight <= container.scrollTop + 10;
                            container.innerHTML = data.html || '<div class=$serviceRequest'text-center py-8$serviceRequest'><p class=$serviceRequest'text-sm text-slate-400$serviceRequest'>No comments yet.</p></div>';
                            if (isScrolledToBottom) {
                                container.scrollTop = container.scrollHeight;
                            }
                        } catch (e) {}
                    }, 5000);
                },
                init() {
                    this.$nextTick(() => {
                        this.$refs.commentsContainer.scrollTop = this.$refs.commentsContainer.scrollHeight;
                    });
                    this.poll();
                }
            }">
                <div class="px-6 py-4 border-b border-slate-200/60">
                    <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                        Comments ({{ $serviceRequest->comments->count() }})
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4 mb-6 max-h-[500px] overflow-y-auto pr-2" x-ref="commentsContainer">
                        @forelse($serviceRequest->comments as $comment)
                            @include('service-requests.partials.comment', ['comment' => $comment])
                        @empty
                            <div class="text-center py-8">
                                <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                                <p class="text-sm text-slate-400">No comments yet. Start the conversation!</p>
                            </div>
                        @endforelse
                    </div>

                    @if($serviceRequest->status !== 'CLOSED')
                        <form action="{{ route('service-requests.comments.store', $serviceRequest) }}" method="POST" x-data="{ commentText: '' }">
                            @csrf
                            @if(isset($cannedResponses) && $cannedResponses->count() > 0)
                                <div class="mb-3 flex justify-end">
                                    <select class="form-select text-sm py-1.5 w-auto" @change="if($event.target.value) { commentText = $event.target.value; $event.target.value=''; }">
                                        <option value="">⚡ Quick Reply...</option>
                                        @foreach($cannedResponses as $cr)
                                            <option value="{{ $cr->content }}">{{ $cr->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="relative">
                                <textarea x-model="commentText" name="comment" rows="3" class="form-input pr-24" placeholder="Write a comment..." required></textarea>
                                <div class="absolute bottom-3 right-3">
                                    <button type="submit" class="btn btn-primary text-xs px-3 py-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                                        Send
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Properties -->
            <div class="glass-card p-6">
                <h3 class="text-base font-bold text-slate-800 mb-4">Details</h3>
                <dl class="space-y-4 text-sm">
                                          <div class="flex items-center justify-between">
                          <dt class="text-slate-500">Category</dt>
                          <dd class="font-medium text-slate-800">{{ $serviceRequest->category->name ?? 'N/A' }}</dd>
                      </div>
                      <div class="flex items-center justify-between">
                          <dt class="text-slate-500">Room</dt>
                          <dd class="font-medium text-slate-800">
                              @if($serviceRequest->room)
                                  <a href="{{ route('rooms.show', $serviceRequest->room_id) }}" class="text-blue-600 hover:underline">
                                      {{ $serviceRequest->room->room_number }} (Floor {{ $serviceRequest->room->floor }})
                                  </a>
                              @else
                                  N/A
                              @endif
                          </dd>
                      </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500">Priority</dt>
                        <dd>
                            <span class="badge {{ $serviceRequest->priority === 'Low' ? 'badge-priority-low' : ($serviceRequest->priority === 'Medium' ? 'badge-priority-medium' : ($serviceRequest->priority === 'High' ? 'badge-priority-high' : 'badge-priority-critical')) }}">{{ $serviceRequest->priority }}</span>
                        </dd>
                    </div>
                    <div class="border-t border-slate-100 pt-4 flex items-center justify-between">
                        <dt class="text-slate-500">Location</dt>
                        <dd class="font-medium text-slate-800">{{ $serviceRequest->location ?? '—' }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500">Device</dt>
                        <dd class="font-medium text-slate-800">{{ $serviceRequest->device ?? '—' }}</dd>
                    </div>
                    <div class="border-t border-slate-100 pt-4 flex items-center justify-between">
                        <dt class="text-slate-500">Assigned</dt>
                        <dd>
                            @if($serviceRequest->assignedUser)
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-red-400 to-purple-500 flex items-center justify-center text-[10px] font-bold text-white">{{ strtoupper(substr($serviceRequest->assignedUser->name, 0, 1)) }}</div>
                                    <span class="font-medium text-slate-800">{{ $serviceRequest->assignedUser->name }}</span>
                                </div>
                            @else
                                <span class="text-slate-400 italic text-xs">Unassigned</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Assign -->
            @can('assign', $serviceRequest)
                <div class="glass-card p-6">
                    <h3 class="text-base font-bold text-slate-800 mb-4">Assign Request</h3>
                    <form action="{{ route('service-requests.assign', $serviceRequest) }}" method="POST" class="space-y-3">
                        @csrf
                        <select name="assigned_to" class="form-input" required>
                            <option value="">Select technician</option>
                            @foreach(App\Models\User::whereIn('role', ['it_support', 'it_admin'])->get() as $itUser)
                                <option value="{{ $itUser->id }}" {{ $serviceRequest->assigned_to == $itUser->id ? 'selected' : '' }}>{{ $itUser->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-dark w-full">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            Assign
                        </button>
                    </form>
                </div>
            @endcan

            <!-- Actions -->
            @if($serviceRequest->status !== 'CLOSED')
                <div class="glass-card p-6 space-y-3">
                    <h3 class="text-base font-bold text-slate-800 mb-2">Actions</h3>

                    @can('resolve', $serviceRequest)
                        @if($serviceRequest->status !== 'RESOLVED')
                            <form action="{{ route('service-requests.update', $serviceRequest) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="RESOLVED">
                                <button type="submit" class="btn btn-success w-full">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Mark Resolved
                                </button>
                            </form>
                        @endif
                    @endcan

                    @can('close', $serviceRequest)
                        <form action="{{ route('service-requests.update', $serviceRequest) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="CLOSED">
                            <button type="submit" class="btn btn-danger w-full" onclick="return confirm('Are you sure?');">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Close Request
                            </button>
                        </form>
                    @endcan
                </div>
            @endif

            @if($serviceRequest->status === 'CLOSED')
                @can('reopen', $serviceRequest)
                    <div class="glass-card p-6">
                        <form action="{{ route('service-requests.update', $serviceRequest) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="OPEN">
                            <button type="submit" class="btn btn-warning w-full">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                                Reopen Request
                            </button>
                        </form>
                    </div>
                @endcan
            @endif
        </div>
    </div>
</x-app-layout>




