<div>
    @if ($popup)
        <div class="fixed bottom-0 right-0">
            <a href="{{ route('contact') }}"
                class="bg-cyan-600 p-5 rounded-full items-center shadow-2xl border border-cyan-700 hover:bg-cyan-700 text-white justify-center flex w-16 h-16 text-2xl mr-2 mb-2">
                <i class="icon icon-comment-2"></i>
            </a>
        </div>
    @else
        <x-pop class="Chats">
            <div class="p-6">
                <x-h3>Chats</x-h3>
            </div>
            <ul class="pb-10" wire:ignore>
                @if ($users->count() > 0)
                    @foreach ($users as $user)
                        <li class="w-full mb-2">
                            <a href="#" data-user="user{{ $user->user_code }}" data-user-id="{{ $user->user_code }}"
                                wire:click.prevent="openChat('{{ $user->user_code }}')"
                                class="flex items-center px-6 px-2 bg-white rounded-lg hover:bg-gray-50 user-chat">
                                <div class="relative">
                                    <img class="w-14 h-14 rounded-full object-cover"
                                        src="{{ $user->profile_photo_url }}" alt="Profile Picture">
                                    <span
                                        class="absolute right-0 bottom-0 block w-4 h-4 rounded-full bg-red-500 border-2 border-white"
                                        data-status="status{{ $user->user_code }}"></span>
                                </div>

                                <div class="ml-3 flex-grow">
                                    <p class="text-gray-900 font-semibold  truncate w-48">
                                        {{ $user->fullName }}</p>
                                    <p class="text-gray-600 text-sm">{{ $user->role->name }}</p>
                                </div>

                                <div class="ml-auto notification-new-messages"
                                    data-new-messagescount="{{ $user->unread_messages_count }}"
                                    data-new-messages="{{ $user->user_code }}">
                                    @if ($user->unread_messages_count != 0)
                                        <span
                                            class="inline-flex items-center justify-center px-2 py-1 ms-2 text-xs font-medium text-white bg-green-500 rounded-full">
                                            {{ $user->unread_messages_count }}
                                        </span>
                                    @endif
                                </div>

                            </a>
                        </li>
                    @endforeach
                @endif

            </ul>
        </x-pop>
        <div class="fixed bottom-0 right-0 z-50" id="chat-container">
            <div class="mr-64">
                @if ($chatActive)
                    @php
                        $counter = 0;
                    @endphp
                    @foreach ($chatActive as $key => $chat)
                        @php
                            $counter++;
                        @endphp
                        <div class="absolute w-[350px] bottom-0"
                            style="right:{{ $counter * 380 }}px; margin-left:15px; margin-right:15px">
                            <div class="flex-1 relative p-6 justify-between flex flex-col h-[550px] shadow bg-white">
                                <div class="flex sm:items-center justify-between px-3 pb-3 border-b-2 border-gray-200">
                                    <div class="relative flex items-center space-x-4">
                                        <div class="relative">
                                            <span wire:ignore
                                                class="absolute bg-red-500 right-0 bottom-0 w-4 h-4 border-2 border-white rounded-full"
                                                data-status="status{{ $chat['user_code'] }}">

                                            </span>
                                            <img src="{{ $chat['profile_photo_url'] }}"
                                                class="w-10 sm:w-12 h-10 sm:h-12 rounded-full object-cover">
                                        </div>
                                        <div class="flex flex-col leading-tight">
                                            <div class="text-2xl mt-1 flex items-center">
                                                <span
                                                    class="text-gray-700 mr-3 font-semibold  truncate w-48 text-sm">{{ $chat['fullName'] }}</span>
                                            </div>
                                            <span class="text-xs text-gray-600">{{ $chat['role_name'] }}</span>
                                        </div>

                                    </div>
                                    <a class="absolute right-0 top-0 text-gray-700 text-2xl p-4 pointer"
                                        wire:click.prevent="removeChat({{ $chat['id'] }})" href="#">
                                        &times;
                                    </a>
                                </div>
                                <div id="messages{{ $chat['user_code'] }}"
                                    class="flex flex-col space-y-4 p-3 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch">


                                    @if (isset($lastMessages[$chat['user_code']]))
                                        @foreach ($lastMessages[$chat['user_code']] as $message)
                                            @if ($message->sender_id == Auth::id())
                                                <div class="chat-message">
                                                    <div class="flex items-end justify-end">
                                                        <div
                                                            class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end">
                                                            <div><span
                                                                    class="px-4 py-2 rounded-lg inline-block rounded-br-none bg-cyan-600 text-white"
                                                                    title="{{ $message->created_at->format('h:i A') }}">{{ $message->content }}</span>
                                                            </div>
                                                        </div>
                                                        <img src="{{ $dataMessages[$chat['user_code']]['sender_profile'] }}"
                                                            class="w-6 h-6 rounded-full order-2">
                                                    </div>
                                                </div>
                                            @else
                                                <div class="chat-message">
                                                    <div class="flex items-end">
                                                        <div
                                                            class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 items-start">
                                                            <div><span
                                                                    class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-200 text-gray-700 "
                                                                    title="{{ $message->created_at->format('h:i A') }}">{{ $message->content }}</span>
                                                            </div>
                                                        </div>
                                                        <img src="{{ $dataMessages[$chat['user_code']]['recipient_profile'] }}"
                                                            class="w-6 h-6 rounded-full order-1">
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                <div class="px-4 pt-4 mb-2 sm:mb-0">
                                    <div class="relative flex">

                                        <x-input type="text" placeholder="Escribe tu mensaje"
                                            data-message-id="{{ $chat['user_code'] }}"
                                            class="w-full focus:placeholder-gray-400 bg-gray-200 -md py-3 send-message-input" />
                                        <x-button type="button" data-set-id="{{ $chat['user_code'] }}"
                                            data-get-id="{{ Auth::user()->user_code }}"
                                            data-profile="{{ $dataMessages[$chat['user_code']]['sender_profile'] }}"
                                            class="w-10 h-10 p-0 flex items-center justify-center text-2xl send-message absolute right-0">
                                            <i class="icon-telegram-2 text-lg"></i>
                                        </x-button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <style>
                            .scrollbar-w-2::-webkit-scrollbar {
                                width: 0.25rem;
                                height: 0.25rem;
                            }

                            .scrollbar-track-blue-lighter::-webkit-scrollbar-track {
                                --bg-opacity: 1;
                                background-color: #f7fafc;
                                background-color: rgba(247, 250, 252, var(--bg-opacity));
                            }

                            .scrollbar-thumb-blue::-webkit-scrollbar-thumb {
                                --bg-opacity: 1;
                                background-color: #edf2f7;
                                background-color: rgba(237, 242, 247, var(--bg-opacity));
                            }

                            .scrollbar-thumb-rounded::-webkit-scrollbar-thumb {
                                border-radius: 0.25rem;
                            }
                        </style>

                        <script></script>
                    @endforeach
                @endif
            </div>
        </div>
    @endif
    @script
        <script>
            Livewire.hook('component.init', ({
                component,
                cleanup
            }) => {

                const userAliasData = "{{ Auth::user()->user_code }}";
                const notificationSound = document.getElementById('notification-sound');

                const socket = io('https://perudevsolutions.com', {
                    query: `user=${encodeURIComponent(userAliasData)}`
                });

                socket.on('receiveMessage', (data) => {
                    if (!notificationSound.paused) {
                        notificationSound.pause();
                        notificationSound.currentTime = 0;
                    }
                    notificationSound.play().catch(error => {
                        console.error('Error al reproducir el sonido:', error);
                    });
                    const messagesContainer = document.getElementById(`messages${data.to}`);

                    const userCode = data.to;
                    /*
                    buscar por usercode y si no hay nada, crear la notificacion, si ya hay, aumentar el messagescount y aumentar el html 
                    */
                    if (messagesContainer == null) {

                        const notification = document.querySelector(`[data-new-messages="${userCode}"]`);
                        if (notification) {
                            if (notification.innerHTML.trim() === "") {
                                // Si no hay HTML, esta es la primera notificación
                                notification.setAttribute('data-new-messagescount', 1);
                                notification.innerHTML = `
                        <span class="inline-flex items-center justify-center px-2 py-1 ms-2 text-xs font-medium text-white bg-green-500 rounded-full">
                            1
                        </span>`;
                            } else {
                                // Si ya hay HTML, incrementar el contador
                                let count = parseInt(notification.getAttribute('data-new-messagescount')) || 0;
                                count++;
                                notification.setAttribute('data-new-messagescount', count);
                                notification.innerHTML = `
                        <span class="inline-flex items-center justify-center px-2 py-1 ms-2 text-xs font-medium text-white bg-green-500 rounded-full">
                            ${count}
                        </span>`;
                            }
                        }
                    } else {
                        const newMessageElement = document.createElement('div');
                        newMessageElement.classList.add('chat-message');
                        newMessageElement.innerHTML =
                            `<div class="flex items-end">
                                                        <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 items-start">
                                                            <div>
                                                                <span class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-200 text-gray-700" title="${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}">
                                                                    ${data.message}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <img src="${data.senderProfile}" class="w-6 h-6 rounded-full order-1">`;

                        messagesContainer.appendChild(newMessageElement);
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    }

                });

                socket.on('connect', () => {
                    console.log('Conectado al servidor de Socket.IO');
                });

                socket.on('disconnect', () => {
                    console.log('Desconectado del servidor de Socket.IO');
                });

                socket.on('users', (userStatus) => {

                    document.querySelectorAll('[data-status]').forEach(statusElement => {
                        const userId = statusElement.getAttribute('data-status').replace('status',
                            '');

                        // Verificar si el usuario está en userStatus
                        const user = userStatus.find(user => user.id === userId);

                        if (user) {
                            // Si el usuario está en userStatus, actualizar la clase
                            statusElement.classList.remove('bg-red-500');
                            statusElement.classList.add('bg-green-500');
                        } else {
                            // Si el usuario no está en userStatus, aplicar la clase de estado ausente
                            statusElement.classList.remove('bg-green-500');
                            statusElement.classList.add('bg-red-500');
                        }
                    });
                });
                /*
                                $wire.on('closedChat', (chatid) => {
                                    socket.emit('getConnectedUsers');
                                });*/
                $wire.on('openedChat', (user_code) => {
                    setTimeout(() => { // Asegúrate de esperar un breve período para que Livewire termine de actualizar el DOM
                        const el = document.getElementById('messages' + user_code);
                        const el_notification = document.querySelector(
                            `[data-new-messages="${user_code}"]`);

                        if (el) {

                            el.scrollTop = el.scrollHeight;
                        }
                        if (el_notification) {

                            el_notification.innerHTML = '';
                            el_notification.setAttribute('data-new-messagescount', '0');
                        }
                        const input = document.querySelector(`input[data-message-id="${user_code}"]`);
                        input.focus();
                        socket.emit('getConnectedUsers');
                    }, 100);
                });

                $wire.on('messageSaved', (user_code) => {
                    const element = document.querySelector(`[data-user-id="${user_code}"]`);
                    if (element) {
                        element.click();
                    }
                });

                const chatContainer = document.getElementById(
                    'chat-container');
                if (chatContainer != null) {
                    chatContainer.addEventListener('keypress', function(event) {
                        if (event.key === 'Enter' && event.target.closest('.send-message-input')) {
                            const input = event.target.closest('.send-message-input');
                            console.log(input);
                            const button = input
                                .nextElementSibling; // Suponiendo que el botón está justo después del input
                            sendMessage(button);
                        }
                    });

                    chatContainer.addEventListener('click', function(event) {
                        if (event.target.closest('.send-message')) {
                            const button = event.target.closest('.send-message');
                            sendMessage(button);
                        }
                    });
                }


                function sendMessage(button) {
                    const userId = button.getAttribute('data-set-id');
                    const to = button.getAttribute('data-get-id');
                    const input = document.querySelector(`input[data-message-id="${userId}"]`);
                    const message = input.value;
                    const senderProfile = button.getAttribute('data-profile');

                    socket.emit('newMessage', {
                        userId,
                        message,
                        to,
                        senderProfile
                    });

                    $wire.sendMessage(userId, message);
                    /*
                                        const messagesContainer = document.getElementById(`messages${userId}`);
                                        const newMessageElement = document.createElement('div');
                                        newMessageElement.classList.add('chat-message');
                                        newMessageElement.innerHTML =
                                            `<div class="flex items-end justify-end">
                                            <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end">
                                                <div>
                                                    <span class="px-4 py-2 rounded-lg inline-block rounded-br-none bg-cyan-600 text-white" title="${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}">
                                                        ${message}
                                                    </span>
                                                </div>
                                            </div>
                                            <img src="${senderProfile}" class="w-6 h-6 rounded-full order-2">`;

                                        messagesContainer.appendChild(newMessageElement);
                                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    */
                    input.value = '';
                    input.focus();
                }

            });
        </script>
    @endscript

</div>
