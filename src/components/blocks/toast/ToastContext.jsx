import React, { createContext, useContext, useState } from 'react';
import ToastContainer from './ToastContainer';

const ToastContext = createContext();

export const useToast = () => {
	const context = useContext(ToastContext);

	if (!context) {
		throw new Error('useToast must be used within a ToastProvider');
	}

	return context;
};

export const ToastProvider = ({ children }) => {
	const [toasts, setToasts] = useState([]);

	const addToast = (message, type = 'info') => {
		setToasts([...toasts, { message, type }]);
	};

	const removeToast = (index) => {
		const newToasts = [...toasts];
		newToasts.splice(index, 1);
		setToasts(newToasts);
	};

	const toastContextValue = {
		addToast, // Make addToast available in the useToast hook
		removeToast,
	};

	return (
		<ToastContext.Provider value={toastContextValue}>
			{children}
			<ToastContainer toasts={toasts} />
		</ToastContext.Provider>
	);
};
