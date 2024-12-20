import {useState} from 'react';
import {__} from '@wordpress/i18n';
import Button from '../button';
import {cancelSubscriptionWithAPI} from './utils';

import ModalDialog from '@givewp/components/AdminUI/ModalDialog';
import DashboardLoadingSpinner from '../dashboard-loading-spinner';
import './style.scss';

type SubscriptionCancelProps = {
    isOpen: boolean;
    toggleModal: () => void;
    id: number;
};

const SubscriptionCancelModal = ({isOpen, toggleModal, id}: SubscriptionCancelProps) => {
    const [loading, setLoading] = useState<boolean>(false);

    const handleCancel = async () => {
        setLoading(true);
        await cancelSubscriptionWithAPI(id);
        setLoading(false);
        toggleModal();
    };

    return (
        <ModalDialog
            wrapperClassName={'give-donor-dashboard-cancel-modal'}
            icon={
                <svg width="20" height="20" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M11 0.5C13.7848 0.5 16.4555 1.60625 18.4246 3.57538C20.3938 5.54451 21.5 8.21523 21.5 11C21.5 13.7848 20.3938 16.4555 18.4246 18.4246C16.4555 20.3938 13.7848 21.5 11 21.5C8.21523 21.5 5.54451 20.3938 3.57538 18.4246C1.60625 16.4555 0.5 13.7848 0.5 11C0.5 8.21523 1.60625 5.54451 3.57538 3.57538C5.54451 1.60625 8.21523 0.5 11 0.5ZM11 5C10.8096 4.99984 10.6212 5.0394 10.4469 5.11615C10.2726 5.1929 10.1162 5.30516 9.98778 5.44575C9.85933 5.58635 9.76161 5.75218 9.70086 5.93268C9.64012 6.11317 9.61769 6.30435 9.635 6.494L10.1825 12.503C10.2037 12.705 10.299 12.892 10.45 13.028C10.6009 13.1639 10.7969 13.2391 11 13.2391C11.2031 13.2391 11.3991 13.1639 11.55 13.028C11.701 12.892 11.7963 12.705 11.8175 12.503L12.3635 6.494C12.3808 6.30447 12.3584 6.11342 12.2978 5.93303C12.2371 5.75264 12.1395 5.58688 12.0112 5.4463C11.8829 5.30573 11.7267 5.19344 11.5526 5.1166C11.3785 5.03976 11.1903 5.00005 11 5ZM11 17C11.3183 17 11.6235 16.8736 11.8485 16.6485C12.0736 16.4235 12.2 16.1183 12.2 15.8C12.2 15.4817 12.0736 15.1765 11.8485 14.9515C11.6235 14.7264 11.3183 14.6 11 14.6C10.6817 14.6 10.3765 14.7264 10.1515 14.9515C9.92643 15.1765 9.8 15.4817 9.8 15.8C9.8 16.1183 9.92643 16.4235 10.1515 16.6485C10.3765 16.8736 10.6817 17 11 17Z"
                        fill="#E6301B"
                    />
                </svg>
            }
            title={__('Cancel Subscription', 'give')}
            showHeader={true}
            isOpen={isOpen}
            handleClose={toggleModal}
        >
            <p className={'give-donor-dashboard-cancel-modal__description'}>
                {__('Are you sure you want to cancel your subscription?', 'give')}
            </p>
            <div className={'give-donor-dashboard-cancel-modal__buttons'}>
                <Button classnames={'give-donor-dashboard-cancel-modal__buttons__button'} disabled={loading} onClick={toggleModal} variant>
                    {__('Nevermind', 'give')}
                </Button>
                <Button classnames={'give-donor-dashboard-cancel-modal__buttons__button'} disabled={loading} onClick={handleCancel}>
                    {__('Yes, cancel', 'give')}
                </Button>
            </div>

            {loading && <DashboardLoadingSpinner />}
        </ModalDialog>
    );
};

export default SubscriptionCancelModal;
