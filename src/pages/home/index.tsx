import { lazy, Suspense } from '@wordpress/element';
import {
  HashRouter,
  Routes,
  Route,
} from "react-router-dom";

import Spinner from '@blocks/preloader/spinner';



const Dashboard = lazy(() => import('@components/dashboard'));
const FormBuilder = lazy(() => import('@components/form-builder'));
const FormBuilderSingle = lazy(() => import('@components/form-builder/single'));
const Setting = lazy(() => import('@components/setting'));
const ProModal = lazy(() => import('@blocks/pro-alert/modal'));

function Home() {

  return (
    <HashRouter>
      <Suspense fallback={''}>
        <ProModal />
      </Suspense>

      <div className="pv-grid-container pv-main-content">

        <div className='pv-right-content pv-bg-pearl'>

          <div className='pv-right-content-data'>
            <Suspense fallback={<Spinner />}>
              <Routes>
                <Route path="/" element={<Dashboard />} />
                <Route path="/form-builder" exact element={<FormBuilder />} />
                <Route path="/form-builder/new" exact element={<FormBuilderSingle />} />
                <Route path="/form-builder/:id/edit" exact element={<FormBuilderSingle />} />
                <Route path="/setting" element={<Setting />} />
                <Route path="/setting/:tab" element={<Setting />} />
                <Route path="/setting/:tab/:subtab" element={<Setting />} />
                <Route path="/setting/:tab/:subtab/:insubtab" element={<Setting />} />
              </Routes>
            </Suspense>
          </div>
        </div>
      </div>
    </HashRouter>
  )
}
export default Home