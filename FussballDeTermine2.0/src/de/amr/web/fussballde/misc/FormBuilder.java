package de.amr.web.fussballde.misc;

import java.awt.GridBagConstraints;
import java.awt.GridBagLayout;
import java.awt.Insets;

import javax.swing.JComponent;
import javax.swing.JPanel;

public class FormBuilder {

	private static final GridBagConstraints left = new GridBagConstraints();
	private static final GridBagConstraints right = new GridBagConstraints();
	private static final GridBagConstraints both = new GridBagConstraints();

	static {
		left.anchor = GridBagConstraints.LINE_END;
		left.gridwidth = GridBagConstraints.RELATIVE;
		left.insets = new Insets(5, 5, 5, 5);

		right.anchor = GridBagConstraints.LINE_START;
		right.gridwidth = GridBagConstraints.REMAINDER;
		right.fill = GridBagConstraints.BOTH;
		right.insets = new Insets(5, 5, 5, 5);

		both.gridwidth = GridBagConstraints.HORIZONTAL;
		both.fill = GridBagConstraints.BOTH;
		both.insets = new Insets(5, 5, 5, 5);
	}

	private final JPanel form;

	public FormBuilder() {
		form = new JPanel(new GridBagLayout());
	}

	public FormBuilder addLeft(JComponent component) {
		form.add(component, left);
		return this;
	}

	public FormBuilder addRight(JComponent component) {
		form.add(component, right);
		return this;
	}

	public FormBuilder addBoth(JComponent component) {
		form.add(component, both);
		return this;
	}

	public JPanel getForm() {
		return form;
	}

}