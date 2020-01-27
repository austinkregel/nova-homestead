const FETCH_HOSTS = 'supervisor/getHosts';
const TOGGLE_VM = 'supervisor/toggleVmStatus';
const FORCE_STOP = 'supervisor/forceStopVm';
const DESTROY_VM = 'supervisor/destroyVm';
const FETCH_HYPERVISORS = 'supervisor/getHypervisors';
const GET_PATH = 'supervisor/getPath';
const SELECT_NEW_PATH = 'supervisor/selectNewPath';
const CREATE_VM = 'supervisor/createVm';
const FETCH_NETWORKS = 'supervisor/getNetworks';
const SYNC_HOST = 'supervisor/syncHost';
const SHUTDOWN = 'supervisor/shutdown';
const REBOOT = 'supervisor/reboot';
const REFRESH_HOSTS_BASED_ON_STATE = 'supervisor/proxyGetHosts';
export default {
    FETCH_HOSTS,
    TOGGLE_VM,
    FORCE_STOP,
    DESTROY_VM,
    GET_PATH,
    FETCH_HYPERVISORS,
    SELECT_NEW_PATH,
    CREATE_VM,
    FETCH_NETWORKS,
    SYNC_HOST,
    SHUTDOWN,
    REBOOT,
    REFRESH_HOSTS_BASED_ON_STATE
}
