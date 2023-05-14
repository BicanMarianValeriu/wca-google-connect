/**
 * @package: 	WeCodeArt Google Connect Extension
 * @author: 	Bican Marian Valeriu
 * @license:	https://www.wecodeart.com/
 * @version:	2.0.2
 */

const {
    i18n: {
        __,
        sprintf
    },
    hooks: {
        addFilter,
        applyFilters
    },
    components: {
        Placeholder,
        BaseControl,
        ExternalLink,
        Spinner,
        Button,
    },
    element: {
        useState,
    }
} = wp;

const { fields } = wecodeartGoogleExtension;

addFilter('wecodeart.admin.extensions', 'wecodeart/google/admin/panel', optionsPanel);
function optionsPanel(panels) {
    return [...panels, {
        name: 'wca-google',
        title: __('Google Connect', 'wca-google'),
        render: (props) => <Options {...props} />
    }];
}

const Options = (props) => {
    const { isRequesting, wecodeartSettings, saveEntityRecord, createNotice } = props;

    if (isRequesting || !wecodeartSettings) {
        return <Placeholder {...{
            icon: <Spinner />,
            label: __('Loading', 'wca-google'),
            instructions: __('Please wait, loading settings...', 'wca-google')
        }} />;
    }

    let googleFields = applyFilters('wecodeart.admin.extensions.google.fields', fields, props);
    googleFields = googleFields.filter(({ id = '', label = '' }) => id !== '' && label !== '');

    const [loading, setLoading] = useState(null);
    const extensionOpts = fields.map(a => a.id);
    const apiOptions = Object.fromEntries(Object.entries(wecodeartSettings).filter(([key]) => extensionOpts.includes(key)));
    const [formData, setFormData] = useState(apiOptions);

    const handleNotice = () => {
        setLoading(false);
        return createNotice('success', __('Settings saved.', 'wca-google'));
    };

    return (
        <>
            <div className="table-responsive">
                <table className="wecodeart-table table table-bordered table-hover">
                    <thead>
                        <tr style={{ textAlign: 'left' }}>
                            <th>{__('Service', 'wca-google')}</th>
                            <th>{__('Code', 'wca-google')}</th>
                            <th>{__('Actions', 'wca-google')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {fields.map(({ id, label, externalUrl = '', placeholder }) => {
                            return (
                                <tr>
                                    <td><strong>{label}</strong></td>
                                    <td>
                                        <BaseControl className="wecodeart-button-field" {...{ id, key: id }}>
                                            <input
                                                id={id}
                                                type="text"
                                                value={formData[id]}
                                                placeholder={placeholder}
                                                onChange={({ target: { value } }) => setFormData({ ...formData, [id]: value })}
                                            />
                                        </BaseControl>
                                    </td>
                                    <td>
                                        <div className="wecodeart-button-group">
                                            {externalUrl !== '' &&
                                                <ExternalLink href={externalUrl} className="wecodeart-button-group__item">
                                                    {__('More info', 'wca-google')}
                                                </ExternalLink>
                                            }
                                        </div>
                                    </td>
                                </tr>
                            );
                        })}
                    </tbody>
                </table>
            </div>
            <Button
                className="button"
                isPrimary
                isLarge
                icon={loading && <Spinner />}
                onClick={() => {
                    setLoading(true);

                    const value = Object.keys(formData).reduce((result, key) => {
                        result[key] = formData[key] === '' ? 'unset' : formData[key];

                        return result;
                    }, {});

                    saveEntityRecord('wecodeart', 'settings', value).then(handleNotice);
                }}
                {...{ disabled: loading }}
            >
                {loading ? '' : __('Save', 'wecodeart')}
            </Button>
        </>
    );
};