const routeModelBinding = require('./route-model-binding').default
export const sync = async function (store, router, options) {
    const moduleName = 'route'

    store.registerModule(moduleName, {
        namespaced: true,
        state: cloneRoute(router.currentRoute),
        mutations: {
            async 'ROUTE_CHANGED' (state, transition) {
                state = cloneRoute(transition.to, transition.from)
                await routeModelBinding(store, transition);
            }
        }
    });

    let isTimeTraveling = false
    let currentPath

    // sync router on store change
    store.watch(
        state => state[moduleName],
        route => {
            const { fullPath } = route
            if (fullPath === currentPath) {
                return
            }
            if (currentPath != null) {
                isTimeTraveling = true
                router.push(route)
            }
            currentPath = fullPath
        },
        { sync: true }
    )

    // sync store on router navigation
    router.afterEach((to, from) => {
        if (isTimeTraveling) {
            isTimeTraveling = false
            return
        }
        currentPath = to.fullPath
        store.commit(moduleName + '/ROUTE_CHANGED', { to, from })
    });
}

function cloneRoute (to, from) {
    const clone = {
        name: to.name,
        path: to.path,
        hash: to.hash,
        query: to.query,
        params: to.params,
        fullPath: to.fullPath,
        meta: to.meta
    }
    if (from) {
        clone.from = cloneRoute(from)
    }
    return Object.freeze(clone)
}
