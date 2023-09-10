import { useState, useEffect } from '@wordpress/element';

const Toast = ({ message, duration, onClose }) => {
	const [isVisible, setIsVisible] = useState(true);

	useEffect(() => {
		const timer = setTimeout(() => {
			setIsVisible(false);
			onClose();
		}, duration);

		return () => clearTimeout(timer);
	}, [duration, onClose]);

	return (
		isVisible && (
			<div
				style={{
					position: 'fixed',
					bottom: '20px',
					left: '50%',
					transform: 'translateX(-50%)',
					background: 'rgba(0, 0, 0, 0.8)',
					color: 'white',
					padding: '10px 20px',
					borderRadius: '4px',
					boxShadow: '0 2px 4px rgba(0, 0, 0, 0.1)',
				}}
			>
				{message}
			</div>
		)
	);
};

const MyComponent = () => {
	const [toasts, setToasts] = useState([]);

	const showToast = (message, duration = 3000) => {
		const newToast = {
			id: Date.now(),
			message,
			duration,
		};

		setToasts([...toasts, newToast]);
	};

	const removeToast = (id) => {
		setToasts(toasts.filter((toast) => toast.id !== id));
	};

	return (
		<div>
			<button onClick={() => showToast("This is a custom toast")}>Show Toast</button>
			<div style={{ position: 'fixed', bottom: '20px', left: '20px' }}>
				{toasts.map((toast) => (
					<Toast
						key={toast.id}
						message={toast.message}
						duration={toast.duration}
						onClose={() => removeToast(toast.id)}
					/>
				))}
			</div>
		</div>
	);
};

export default MyComponent;
