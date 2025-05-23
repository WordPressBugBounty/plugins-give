import type {SelectableFieldProps} from '@givewp/forms/propTypes';
import autoCompleteAttr from '@givewp/forms/registrars/templates/fields/utils/autoCompleteAttr';

/**
 * @since 4.3.0 Add autoComplete support
 */
export default function Select({
    Label,
    ErrorMessage,
    placeholder,
    fieldError,
    options,
    description,
    inputProps,
}: SelectableFieldProps) {
    const autoComplete = autoCompleteAttr(inputProps?.name);
    const FieldDescription = window.givewp.form.templates.layouts.fieldDescription;

    return (
        <label>
            <Label />
            {description && <FieldDescription description={description} />}
            <select {...inputProps} aria-invalid={fieldError ? 'true' : 'false'} autoComplete={autoComplete}>
                {placeholder && (
                    <>
                        <option hidden>{placeholder}</option>
                        <option disabled>{placeholder}</option>
                    </>
                )}
                {options.map(({value, label}) => (
                    <option key={value} value={value}>
                        {label ?? value}
                    </option>
                ))}
            </select>

            <ErrorMessage />
        </label>
    );
}
