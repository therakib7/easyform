import { useCallback, useRef, lazy, Suspense, useEffect, useState } from '@wordpress/element';
import Spinner from '@blocks/preloader/spinner';
import { NavLink } from "react-router-dom";
import useClickOutside from '@blocks/outside-click';

const Summary = lazy(() => import('./section/Summary'));

// import Toast from '@blocks/toast';

import { useToast } from '@blocks/toast/ToastContext';
const Dashboard = (props) => {
    const [dropdown, setDropdown] = useState(false);
    const [year, setYear] = useState(2023);

    const dropdownRef = useRef();
    const close = useCallback(() => setDropdown(false), []);
    useClickOutside(dropdownRef, close);

    // const showToast = (message, type) => {
    //     const toastComponent = document.getElementById('toast');
    //     if (toastComponent) {
    //         toastComponent.showToast(message, type);
    //     }
    // };

    const toast = useToast();

    const showToast = () => {
        toast.addToast('This is a custom toast!', 'success');
    };

    const { i18n } = rhef;
    return (
        <div className="rhef-dashboard">
            <div className="row">
                <div className="col">
                    <h2
                        onClick={() => { console.log(Toast) }}
                        className="pv-page-title"
                        style={{ color: "#2d3748", display: "inline-block" }}
                    >
                        Dashboard
                    </h2>
                </div>

                <div className="col">
                    <button onClick={showToast}>Show Toast</button>
                </div>
            </div>

            <Suspense fallback={<Spinner />}>
                {/* <Summary {...props} /> */}
            </Suspense>

        </div>
    );
}
export default Dashboard;