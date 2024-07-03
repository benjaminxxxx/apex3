<div>
    <x-h2 value="Miembros Activos"/>
    <x-hr/>
    <div class="flex flex-wrap gap-4 active-users-items"></div>
    @script
        <script>
            $wire.on('userDataUpdated', (userDataJson) => {
                
                const userAliasData = JSON.parse(userDataJson);
                console.log(userAliasData);
                const socket = io('http://localhost:3000', {
                    query: `name=${encodeURIComponent(userAliasData.name)}&avatar=${encodeURIComponent(userAliasData.avatar)}&id=${encodeURIComponent(userAliasData.id)}`
                });

                socket.on('connect', () => {
                    console.log('Conectado al servidor de Socket.IO');
                });

                socket.on('disconnect', () => {
                    console.log('Desconectado del servidor de Socket.IO');
                });

                socket.on('users', (users) => {
                    const activeUsersContainer = document.querySelector('.active-users-items');
                    activeUsersContainer.innerHTML = '';

                    users.forEach(user => {
                        const avatarUrl = user.avatar;
                        const userName = user.name;

                        const avatarElement = document.createElement('div');
                        avatarElement.classList.add('relative');
                        avatarElement.innerHTML = `
             <img src="${avatarUrl}" alt="Avatar de ${userName}"
                 class="w-16 h-16 rounded-full cursor-pointer"
                 data-tippy-content="${userName}" />
         `;
                        activeUsersContainer.appendChild(avatarElement);
                    });
                    tippy('[data-tippy-content]', {
                        placement: 'top',
                        allowHTML: true,
                    });
                });
            });
        </script>
    @endscript

</div>
