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

window.HOMESTEAD_LOADED = {
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

    store.registerModule('homestead', require('./store').default);
    await sync(store, router);

    await store.dispatch(E.FETCH_HYPERVISORS, {}, { root: true})
    // await store.dispatch(E.FETCH_HOSTS, {}, { root: true})
    Vue.component('host', require('./components/Host'));
    Vue.component('hypervisor', require('./components/Hypervisor'));

    const routes = [
        route(undefined, 'homestead', './routes/Base', {
            children: [
                route('homestead', 'homestead', './routes/Homestead'),
                route('homestead.index', 'homestead/:hypervisor/virtual-machines', './routes/VirtualMachine/Index'),
                route('homestead.create', 'homestead/:hypervisor/virtual-machines/create', './routes/VirtualMachine/Create'),
                route('homestead.show', 'homestead/:hypervisor/virtual-machine/:host', './routes/VirtualMachine/Show', {
                    children: [

                        route('homestead.show.access', 'homestead/:hypervisor/virtual-machine/:host/access', './routes/VirtualMachine/Show/Access'),
                        route('homestead.show.destroy', 'homestead/:hypervisor/virtual-machine/:host/destroy', './routes/VirtualMachine/Show/Destroy'),
                        route('homestead.show.devices', 'homestead/:hypervisor/virtual-machine/:host/devices', './routes/VirtualMachine/Show/Devices'),
                        route('homestead.show.power', 'homestead/:hypervisor/virtual-machine/:host/power', './routes/VirtualMachine/Show/Power'),
                        route('homestead.show.resize', 'homestead/:hypervisor/virtual-machine/:host/resize', './routes/VirtualMachine/Show/Resize'),
                        route('homestead.show.snapshots', 'homestead/:hypervisor/virtual-machine/:host/snapshots', './routes/VirtualMachine/Show/Snapshots'),
                        route('homestead.show.tags', 'homestead/:hypervisor/virtual-machine/:host/tags', './routes/VirtualMachine/Show/Tags'),
                    ]
                }),
                route('homestead.edit', ':hypervisor/virtual-machine/:host/edit', './routes/VirtualMachine/Edit'),
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

