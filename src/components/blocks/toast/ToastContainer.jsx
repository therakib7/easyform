import React from 'react';
// import './ToastContainer.css'; // You can style this component as you like

const ToastContainer = ({ toasts }) => {
	return (
		<div className="toast-container">
			{toasts.map((toast, index) => (
				<div key={index} className={`toast toast-${toast.type}`}>
					{toast.message}
				</div>
			))}
		</div>
	);
};

export default ToastContainer;
