import Swal from 'sweetalert2';
import { usePage } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import { isEqual } from 'lodash';

function useToast() {
	const toast = Swal.mixin({
		toast: true,
		position: "top-end",
		showConfirmButton: false,
		timer: 5000,
		timerProgressBar: true,
		didOpen: (toast) => {
			toast.onmouseenter = Swal.stopTimer;
			toast.onmouseleave = Swal.resumeTimer;
		}
	});

	const showMessage = (message) => {
		toast.fire({
			text: message.message,
			icon: message.type,
		})
	}

	const watchMessage = () => {
		watch(() => usePage().props.message, (newVal, oldVal) => {
			if (newVal?.id !== oldVal?.id) {
				showMessage(newVal);
			}
		});
	}

	return {
		showMessage,
		watchMessage,
	};
}

export {
	useToast,
};
