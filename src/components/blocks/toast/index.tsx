import { useState, useEffect } from '@wordpress/element';
import './style.scss';

const Toast = ({ message, type }) => {
	const [isVisible, setIsVisible] = useState(false);

	useEffect(() => {
		if (message && type) {
			setIsVisible(true);
			const timer = setTimeout(() => {
				setIsVisible(false);
			}, 3000);

			return () => clearTimeout(timer);
		}
	}, [message, type]);

	return (
		<div id="toast" className={`toast ${type} ${isVisible ? 'visible' : ''}`}>
			<p>{message}</p>
		</div>
	);
};

export default Toast;