import routeModelBinding from './route-model-binding';

const formatHost = (host) => {
    if (!host) {
        return null;
    }

    let osIcon = (() => {
        switch (host.os) {
            case 'http://ubuntu.com/ubuntu/18.04':
            default:
                return 'ubuntu.png';
        }
    })()
    let osName = (() => {
        switch (host.os) {
            case 'http://ubuntu.com/ubuntu/18.04':
                return 'Ubuntu 18.04 LTS'
            default:
                return host.os;
        }
    })();

    let memoryReadable = parseInt(host.memory)/1024/1024

    let disks = host.devices.filter(device => device.device === 'disk').map(disk => {
        disk.disk_size_readable = Math.round(disk.disk_size/ 1024/1024/1024)
        return disk;
    })


    let networks = host.networks.map(network => {
        network.private = /(^127\.)|(^10\.)|(^172\.1[6-9]\.)|(^172\.2[0-9]\.)|(^172\.3[0-1]\.)|(^192\.168\.)/.test(network.ip)

        return network;
    })

    return Object.assign(host, {
        shouldBeOn: host.is_enabled,
        networks,
        disks,
        memoryReadable,
        osIcon,
        osName,
    })
}
const root = true;

export default {
    namespaced: true,
    state: {
        hypervisors: null,
        hosts: null,
        path: null,
        networks: null,
        files: null,
        busy: true,
        shouldShowEdit: false,
        host: null,
        hypervisor: null
    },
    actions: {
        async getHosts({ commit, dispatch }, { params, meta }) {
            commit('toggleBusy', true);
            if (!params.hypervisor) {
                commit('toggleBusy', false);
                return;
            }

            try {
                let { data } = await axios.get('/nova-vendor/homestead/hypervisors/' + params.hypervisor + '/machines')
                commit('hosts', Array.isArray(data) ? data : []);
                await dispatch(Nova.E.SYNC_HOST, { params, meta }, { root })
                return data;
            } catch (e) {
                commit('toggleBusy', false);
            }
        },

        async proxyGetHosts({ commit, dispatch, state }) {
            commit('toggleBusy', true);
            if (!state.hypervisor) {
                commit('toggleBusy', false);
                return;
            }

            try {
                let {data} = await axios.get('/nova-vendor/homestead/hypervisors/' + state.hypervisor.id + '/machines')
                commit('hosts', Array.isArray(data) ? data : []);
                await dispatch(Nova.E.SYNC_HOST, Nova.app.$route, { root })
                return data;
            } catch (e) {
                commit('toggleBusy', false);
            }
        },

        async toggleVmStatus({ dispatch, commit }, host) {
            commit('toggleBusy', true);
            try {
                let newStatus = !host.shouldBeOn ? 'enable' : 'shutdown';
                dispatch(newStatus, host)
            } finally {
                commit('toggleBusy', false);
            }
        },

        async shutdown({ dispatch, commit, state }, host) {
            try {
                await axios.post('/nova-vendor/homestead/hypervisors/' + state.hypervisor.id + '/virtual-machines/' + host.uuid + '/shutdown')
                Nova.app.$toasted.success(host.name + ' is being shutdown safely.');
                dispatch(Nova.E.REFRESH_HOSTS_BASED_ON_STATE, null, { root })
            } finally {
                commit('toggleBusy')
            }
        },

        async enable({ dispatch, commit, state }, host) {
            try {
                await axios.post('/nova-vendor/homestead/hypervisors/' + state.hypervisor.id + '/virtual-machines/' + host.uuid + '/enable')
                Nova.app.$toasted.success(host.name + ' is being enabled.');
                dispatch(Nova.E.REFRESH_HOSTS_BASED_ON_STATE, null, { root })
            } finally {
                commit('toggleBusy')
            }
        },

        async reboot({ dispatch, commit, state }, host) {
            try {
                await axios.post('/nova-vendor/homestead/hypervisors/' + state.hypervisor.id + '/virtual-machines/' + host.uuid + '/reboot')
                Nova.app.$toasted.success(host.name + ' is being rebooted.');
                dispatch(Nova.E.REFRESH_HOSTS_BASED_ON_STATE, null, { root })
            } finally {
                commit('toggleBusy')
            }
        },

        async forceStopVm({ commit, state }, host) {
            commit('toggleBusy', true);
            try {
                await axios.post('/nova-vendor/homestead/hypervisors/' + state.hypervisor.id + '/virtual-machines/' + host.uuid + '/force-stop')
                Nova.app.$toasted.success(host.name + ' has been shut down.')
            } finally {
                commit('toggleBusy', false);
            }
        },

        async destroyVm({ state, dispatch, commit }, host) {
            commit('toggleBusy', true);
            try {
                await axios.post('/nova-vendor/homestead/hypervisors/' + state.hypervisor.id + '/virtual-machines/' + host.uuid + '/destroy')
                commit('toggleBusy', false);
                Nova.app.$toasted.success(host.name + ' has been destroyed.')
                dispatch(Nova.E.REFRESH_HOSTS_BASED_ON_STATE, null, { root })
            } finally {
                commit('toggleBusy', true);
            }
        },

        async getHypervisors({ commit }) {
            let { data } = await axios.get('/nova-vendor/homestead/hypervisors')
            commit('hypervisors', data);

            return data;
        },

        async getPath({ dispatch, commit, state }, $route) {
            let hypervisor = state.hypervisors.filter(hypervisor => hypervisor.id == $route.params.hypervisor)[0]

            if (!hypervisor) {
                Nova.app.$toasted.error('There are no hypervisors found by ID: ' + $route.params.hypervisor)
                return;
            }

            commit('path', hypervisor.path_to_isos);

            const { data } = await axios.get('/nova-vendor/homestead/iso-files/' + btoa(state.path))

            commit('files', data);
        },

        async selectNewPath({ dispatch, state, commit }, file) {
            if (file.path.endsWith('.iso')) {
                this.selectedIso = file;
                return ;
            }

            commit('path', state.path+file.path+'/')
        },

        async createVm({ state, commit, dispatch}, { form, selectedIso, selectedNetwork }) {
            form.disk.path = form.disk.path + form.name + '.' + form.disk.driver;
            form.iso = state.path + '/' + selectedIso.path;

            const { data } = await axios.post('/nova-vendor/homestead/hypervisors/' + state.hypervisor.id + '/virtual-machines', form)
            Nova.app.$toasted.success(data.name + ' created!')
            dispatch(Nova.E.REFRESH_HOSTS_BASED_ON_STATE, null, { root })
        },

        async getNetworks({ commit }, $route) {
            let { data } = await axios.post('/nova-vendor/homestead/network', {
                hypervisor_id: $route.params.hypervisor
            })

            commit('networks', data);
        },

        async syncHost(store, $route) {
            await routeModelBinding(store, { to: $route });
        }
    },
    getters: {
        host(state) {
            return formatHost(state.host);
        },
        hosts(state) {
            if (!state.hosts) {
                return null;
            }
            return state.hosts.map(formatHost)
        }
    },
    mutations: {
        toggleBusy(state, value) {
            state.busy = value;
        },
        files(state, files) {
            state.files = files;
        },
        path(state, path) {
            state.path = path;
        },
        networks(state, networks) {
            state.networks = networks;
        },
        hypervisors(state, hypervisors) {
            state.hypervisors = hypervisors;
        },
        hosts(state, hosts) {
            state.hosts = hosts;
        },
        hypervisor(state, hypervisor) {
            state.hypervisor = hypervisor;
        },
        host(state, host) {
            state.host = host;
        },
    }
}
