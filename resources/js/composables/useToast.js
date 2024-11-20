
import { ref } from 'vue'

const showToast = ref(false)
const toastMessage = ref('')

export function useToast() {
    const showSuccessToast = (message) => {
        toastMessage.value = message
        showToast.value = true
        setTimeout(() => {
            showToast.value = false
        }, 5000)
    }

    return {
        showToast,
        toastMessage,
        showSuccessToast
    }
}