import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

export function usePermissions() {
    const page = usePage()

    const permissions = computed(() => page.props.permissions || [])

    const can = (permission) => {
        return permissions.value.includes(permission)
    }

    const canAny = (perms = []) => {
        return perms.some(p => permissions.value.includes(p))
    }

    const canAll = (perms = []) => {
        return perms.every(p => permissions.value.includes(p))
    }

    return {
        permissions,
        can,
        canAny,
        canAll,
    }
}
