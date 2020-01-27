import { plural, singular } from 'pluralize';

export default async (store, { to, from}) => {
    for(let bindingKey in to.params) {
        try {
            let pluralKey = plural(bindingKey);
            let singularKey = singular(bindingKey);

            let value = to.params[bindingKey];

            if(!store.state.supervisor){
                continue;
            }
            // This means we're not storing the value in our state.
            if (typeof store.state.supervisor[pluralKey] === 'undefined') {
                continue;
            }

            let pluralValue = store.state.supervisor[pluralKey];

            // We're storing it, but we haven't received it from the server yet.
            if (pluralValue === null) {
                if (typeof Nova.E['FETCH_' + pluralKey.toUpperCase()] === 'undefined') {
                    throw `Unknown fetch! ${'FETCH_' + pluralKey.toUpperCase()}`
                }
                let supervisorValue = Nova.E['FETCH_' + pluralKey.toUpperCase()];
                pluralValue = await store.dispatch(supervisorValue, to, {root: true})
            }

            if (!Array.isArray(pluralValue)) {
                console.error(pluralValue, 'invalid plural value...');
                continue;
            }

            const boundModel = pluralValue.filter(item => {
                return item[to.meta[singularKey]] == value
            })[0]

            if (boundModel) {
                store.commit('supervisor/' + singularKey, boundModel, {root: true});
            }
        } catch (e) {
            console.log('We had an error in the route model binding', { to, bindingKey, e })
        }
    }
}
