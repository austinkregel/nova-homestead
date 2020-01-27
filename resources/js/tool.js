import Vuex from 'vuex';
import E from './constants';
import { sync } from './vuex-route-sync'
Nova.E = E
Nova.route = (name, parameters) => {
    let namedRoute = Nova.app.$router.options.routes.filter(route => route.name === name)[0]
    if (!namedRoute) {
        throw "No valid route"
    }

    namedRoute.resolvedPath = namedRoute.path;

    for (let key in parameters) {
        namedRoute.resolvedName = namedRoute.resolvedName.replace(':' + key, parameters[key])
    }

    return namedRoute;
}

window.SUPERVISOR_LOADED = {
    hypervisors: false,
    hosts: false,
}

const route = (name, path, component, options) => Object.assign({
    path: '/' + path,
    name,
    component: require(`${component}`),
    meta: {
        hypervisor: 'id',
        host: 'uuid'
    }
}, options);

Nova.booting(async (Vue, router, store) => {
    Vue.use(Vuex);
    Vue.config.devtools = true;

    store.registerModule('supervisor', require('./store').default);
    await sync(store, router);

    await store.dispatch(E.FETCH_HYPERVISORS, {}, { root: true})
    // await store.dispatch(E.FETCH_HOSTS, {}, { root: true})
    Vue.component('host', require('./components/Host'));
    Vue.component('hypervisor', require('./components/Hypervisor'));

    const routes = [
        route(undefined, 'supervisor', './routes/Base', {
            children: [
                route('supervisor', 'supervisor', './routes/Supervisor'),
                route('supervisor.index', 'supervisor/:hypervisor/virtual-machines', './routes/VirtualMachine/Index'),
                route('supervisor.create', 'supervisor/:hypervisor/virtual-machines/create', './routes/VirtualMachine/Create'),
                route('supervisor.show', 'supervisor/:hypervisor/virtual-machine/:host', './routes/VirtualMachine/Show', {
                    children: [

                        route('supervisor.show.access', 'supervisor/:hypervisor/virtual-machine/:host/access', './routes/VirtualMachine/Show/Access'),
                        route('supervisor.show.destroy', 'supervisor/:hypervisor/virtual-machine/:host/destroy', './routes/VirtualMachine/Show/Destroy'),
                        route('supervisor.show.devices', 'supervisor/:hypervisor/virtual-machine/:host/devices', './routes/VirtualMachine/Show/Devices'),
                        route('supervisor.show.power', 'supervisor/:hypervisor/virtual-machine/:host/power', './routes/VirtualMachine/Show/Power'),
                        route('supervisor.show.resize', 'supervisor/:hypervisor/virtual-machine/:host/resize', './routes/VirtualMachine/Show/Resize'),
                        route('supervisor.show.snapshots', 'supervisor/:hypervisor/virtual-machine/:host/snapshots', './routes/VirtualMachine/Show/Snapshots'),
                        route('supervisor.show.tags', 'supervisor/:hypervisor/virtual-machine/:host/tags', './routes/VirtualMachine/Show/Tags'),
                    ]
                }),
                route('supervisor.edit', ':hypervisor/virtual-machine/:host/edit', './routes/VirtualMachine/Edit'),
            ]
        })
    ];

    router.addRoutes(routes);

    // This is just so we can search for the routes later....
    router.options.routes.push(...routes.map(route => {
        delete route['component'];
        return route;
    }));
})

