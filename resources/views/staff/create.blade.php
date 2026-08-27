<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-900">Add User / Staff</h1>
    </x-slot>

    <div class="max-w-2xl">
        <div class="glass-card p-6 md:p-8">
            <form action="{{ route('staff.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" id="name" class="form-input @error('name') border-red-500 @enderror" value="{{ old('name') }}" required autofocus placeholder="e.g. John Doe">
                    @error('name')
                        <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="form-label">Email Address (Optional)</label>
                    <input type="email" name="email" id="email" class="form-input @error('email') border-red-500 @enderror" value="{{ old('email') }}" placeholder="e.g. john@hotel.com">
                    @error('email')
                        <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Job Title -->
                <div class="md:col-span-2">
                    <label for="job_title" class="form-label">Job Title / Position (Optional)</label>
                    <input type="text" name="job_title" id="job_title" class="form-input @error('job_title') border-red-500 @enderror" value="{{ old('job_title') }}" placeholder="e.g. Front Desk Manager, Housekeeping Supervisor">
                    @error('job_title')
                        <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="form-label">Role</label>
                    <select name="role" id="role" class="form-input @error('role') border-red-500 @enderror" required>
                        <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                        <option value="it_support" {{ old('role') == 'it_support' ? 'selected' : '' }}>IT Support</option>
                        <option value="it_admin" {{ old('role') == 'it_admin' ? 'selected' : '' }}>IT Admin</option>
                    </select>
                    @error('role')
                        <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Department -->
                <div>
                    <label for="department_id" class="form-label">Department</label>
                    <select name="department_id" id="department_id" class="form-input @error('department_id') border-red-500 @enderror">
                        <option value="">No Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-input @error('password') border-red-500 @enderror" required>
                        @error('password')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('staff.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        Add User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
