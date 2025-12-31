<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Team Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Invite Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Invite New Member') }}</h3>
                <div class="text-sm text-gray-600 mb-4">
                    {{ __('Usage: :count / :max slots used.', ['count' => $members->count() + $invitations->count(), 'max' => $maxSubAdmins]) }}
                </div>

                @if($canInvite)
                    <form method="POST" action="{{ route('tenant.team.store') }}" class="flex gap-4 items-end">
                        @csrf
                        <div class="flex-grow">
                            <x-input-label for="email" :value="__('Email Address')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required />
                        </div>
                        <x-primary-button>
                            {{ __('Send Invitation') }}
                        </x-primary-button>
                    </form>
                @else
                    <div class="p-4 bg-yellow-100 text-yellow-800 rounded-md">
                        {{ __('You have reached the maximum number of team members for your plan.') }}
                    </div>
                @endif
            </div>

            <!-- Pending Invitations -->
            @if($invitations->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Pending Invitations') }}</h3>
                    <ul class="divide-y divide-gray-200">
                        @foreach($invitations as $invite)
                            <li class="py-4 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $invite->email }}</p>
                                    <p class="text-xs text-gray-500">{{ __('Expires:') }} {{ $invite->expires_at->diffForHumans() }}</p>
                                </div>
                                <form method="POST" action="{{ route('tenant.team.destroy', $invite) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:text-red-900 text-sm">{{ __('Cancel') }}</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Team Members -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Team Members') }}</h3>
                @if($members->count() > 0)
                     <ul class="divide-y divide-gray-200">
                        @foreach($members as $member)
                            <li class="py-4 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $member->email }}</p>
                                </div>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $member->role }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 text-sm">{{ __('No other team members yet.') }}</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
