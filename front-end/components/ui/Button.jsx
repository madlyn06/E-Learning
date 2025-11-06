/**
 * @typedef {"primary" | "secondary" | "third" | "fourth" | "fifth"} ButtonVariant
 */

/**
 * @typedef ButtonProps
 * @property {import("react").ReactNode} [children]
 * @property {ButtonVariant} [variant]
 * @property {string} [icon]
 * @property {"button" | "submit" | "reset"} [type]
 * @property {string} [className]
 * @property {boolean} [disabled]
 */

/**
 * @param {ButtonProps & import("react").ButtonHTMLAttributes<HTMLButtonElement>} props
 */
export function Button({
  children,
  variant = "primary",
  icon = "icon-arrow-top-right",
  type = "button",
  className = "",
  disabled = false,
  ...rest
}) {
  return (
    <button
      type={type}
      className={`button-submit tf-btn wow fadeInUp style-${variant} ${className}`}
      data-wow-delay="0s"
      {...rest}
    >
      {children}
      {icon && !disabled && <i className={icon} />}
      {disabled && <div className="loading-spinner-icon" />}
    </button>
  )
}
