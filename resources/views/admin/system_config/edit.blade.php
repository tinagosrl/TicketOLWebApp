<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('System Configuration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Main Configuration Form -->
            <form action="{{ route('admin.system_config.update') }}" method="POST" x-data="{ mailer: '{{ $settings['mail_mailer'] ?? 'smtp' }}' }">
                @csrf
                @method('PATCH')

                <!-- Mail Settings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Mail Configuration') }}</h3>
                            <div class="w-1/3">
                                <x-input-label for="mail_mailer" value="Driver" />
                                <select id="mail_mailer" name="mail_mailer" x-model="mailer" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="smtp">SMTP</option>
                                    <option value="mailgun">Mailgun</option>
                                    <option value="log">Log (Local)</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Common Settings -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                             <div>
                                <x-input-label for="mail_from_address" value="From Address" />
                                <x-text-input id="mail_from_address" name="mail_from_address" type="email" class="mt-1 block w-full" :value="$settings['mail_from_address'] ?? ''" />
                            </div>
                             <div>
                                <x-input-label for="mail_from_name" value="From Name" />
                                <x-text-input id="mail_from_name" name="mail_from_name" type="text" class="mt-1 block w-full" :value="$settings['mail_from_name'] ?? ''" />
                            </div>
                        </div>

                        <!-- SMTP Specific -->
                        <div x-show="mailer === 'smtp'" class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t pt-4">
                            <div class="col-span-2"><h4 class="font-medium text-gray-700">SMTP Settings</h4></div>
                            <div>
                                <x-input-label for="mail_host" value="Mail Host" />
                                <x-text-input id="mail_host" name="mail_host" type="text" class="mt-1 block w-full" :value="$settings['mail_host'] ?? ''" />
                            </div>
                            <div>
                                <x-input-label for="mail_port" value="Mail Port" />
                                <x-text-input id="mail_port" name="mail_port" type="text" class="mt-1 block w-full" :value="$settings['mail_port'] ?? ''" />
                            </div>
                             <div>
                                <x-input-label for="mail_encryption" value="Encryption (tls/ssl)" />
                                <x-text-input id="mail_encryption" name="mail_encryption" type="text" class="mt-1 block w-full" :value="$settings['mail_encryption'] ?? ''" />
                            </div>
                            <div>
                                <x-input-label for="mail_username" value="Mail Username" />
                                <x-text-input id="mail_username" name="mail_username" type="text" class="mt-1 block w-full" :value="$settings['mail_username'] ?? ''" />
                            </div>
                            <div>
                                <x-input-label for="mail_password" value="Mail Password" />
                                <x-text-input id="mail_password" name="mail_password" type="password" class="mt-1 block w-full" :value="$settings['mail_password'] ?? ''" />
                            </div>
                        </div>

                        <!-- Mailgun Specific -->
                        <div x-show="mailer === 'mailgun'" class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t pt-4" style="display: none;">
                            <div class="col-span-2"><h4 class="font-medium text-gray-700">Mailgun Settings</h4></div>
                            <div>
                                <x-input-label for="mailgun_domain" value="Mailgun Domain" />
                                <x-text-input id="mailgun_domain" name="mailgun_domain" type="text" class="mt-1 block w-full" :value="$settings['mailgun_domain'] ?? ''" placeholder="mg.domain.com" />
                            </div>
                            <div>
                                <x-input-label for="mailgun_secret" value="Mailgun Secret (API Key)" />
                                <x-text-input id="mailgun_secret" name="mailgun_secret" type="password" class="mt-1 block w-full" :value="$settings['mailgun_secret'] ?? ''" />
                            </div>
                             <div>
                                <x-input-label for="mailgun_endpoint" value="Mailgun Endpoint" />
                                <x-text-input id="mailgun_endpoint" name="mailgun_endpoint" type="text" class="mt-1 block w-full" :value="$settings['mailgun_endpoint'] ?? 'api.mailgun.net'" placeholder="api.mailgun.net or api.eu.mailgun.net" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SMS Settings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('SMS Configuration (Aruba)') }}</h3>
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="aruba_sms_username" value="SMS Username" />
                                <x-text-input id="aruba_sms_username" name="aruba_sms_username" type="text" class="mt-1 block w-full" :value="$settings['aruba_sms_username'] ?? ''" />
                            </div>
                            <div>
                                <x-input-label for="aruba_sms_password" value="SMS Password" />
                                <x-text-input id="aruba_sms_password" name="aruba_sms_password" type="password" class="mt-1 block w-full" :value="$settings['aruba_sms_password'] ?? ''" />
                            </div>
                             <div>
                                <x-input-label for="aruba_sms_sender" value="Sender Name/Number" />
                                <x-text-input id="aruba_sms_sender" name="aruba_sms_sender" type="text" class="mt-1 block w-full" :value="$settings['aruba_sms_sender'] ?? ''" />
                            </div>
                        </div>
                    </div>
                </div>

                
                <!-- SEO & AI Settings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('SEO & AI Optimization') }}</h3>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="seo_meta_description" value="Meta Description (Global)" />
                                <textarea id="seo_meta_description" name="seo_meta_description" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" rows="2">{{ $settings['seo_meta_description'] ?? '' }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Short description for search engines.</p>
                            </div>
                            
                            <div>
                                <x-input-label for="seo_keywords" value="Meta Keywords" />
                                <x-text-input id="seo_keywords" name="seo_keywords" type="text" class="mt-1 block w-full" :value="$settings['seo_keywords'] ?? ''" placeholder="tickets, museums, booking..." />
                            </div>

                            <div class="border-t pt-4 mt-2">
                                <x-input-label for="llm_context" value="LLM Context / AI Directives" />
                                <textarea id="llm_context" name="llm_context" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm font-mono text-xs bg-gray-50" rows="4">{{ $settings['llm_context'] ?? '' }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">
                                    Text injected as a comment or hidden meta tag to help AI bots understand this site.
                                    <br>Example: "This is TicketOL, a platform for booking museum tickets in Italy."
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
    
<div class="flex items-center justify-end mt-4">
                     <x-primary-button class="ml-4">
                        {{ __('Save Configuration') }}
                    </x-primary-button>
                </div>
            </form>

            <!-- Test Email Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                 <div class="p-6 bg-white border-b border-gray-200">
                     <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Test Configuration') }}</h3>
                     <form action="{{ route('admin.system_config.test_email') }}" method="POST" class="flex gap-4 items-end">
                         @csrf
                         <div class="flex-grow">
                             <x-input-label for="test_email" value="Recipient Email" />
                             <x-text-input id="test_email" name="test_email" type="email" class="mt-1 block w-full" placeholder="your@email.com" required />
                         </div>
                         <x-secondary-button type="submit">
                             {{ __('Send Test Email') }}
                         </x-secondary-button>
                     </form>
                 </div>
            </div>

        </div>
    </div>
</x-app-layout>
