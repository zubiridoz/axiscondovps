import sys

filepath = 'app/Controllers/Admin/FinanceController.php'
with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Replace the signature spacing block
old_block = """            if (is_file($signaturePath)) {
                $hasSignature = true;
                $pdf->Ln(4);

                $imgWidth = 35;
                $imgHeight = 12;
                $centerX = 20 + (175.6 - $imgWidth) / 2;

                $pdf->Image($signaturePath, $centerX, $pdf->GetY(), $imgWidth, $imgHeight, '', '', '', false, 300, '', false, false, 0, 'CM', false, false);
                $pdf->Ln($imgHeight + 1);

                $pdf->SetTextColor(15, 23, 42);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->Cell(175.6, 5, $signatureName, 0, 1, 'C');
                $pdf->Ln(4);
            }
        }

        if (!$hasSignature) {
            $pdf->Ln(8);
        }

        // ── AVISO FISCAL ──
        $pdf->SetFillColor(255, 251, 235); // amber-50
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetTextColor(146, 64, 14);
        $noticeY = $pdf->GetY();
        $pdf->Cell(175.6, 6, '  Aviso fiscal: Este recibo no constituye un CFDI y no tiene validez fiscal para efectos de deducción de impuestos.', 0, 1, 'L', true);

        $pdf->Ln(5);

        // ── OBSERVACIONES ──
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(175.6, 7, 'Observaciones', 0, 1, 'L');
        $pdf->SetDrawColor(226, 232, 240);
        $pdf->Line(20, $pdf->GetY(), 195.6, $pdf->GetY());
        $pdf->Ln(2);

        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->Cell(175.6, 5, 'Conserve este recibo para sus registros. El pago se refleja en su estado de cuenta.', 0, 1, 'L');

        $pdf->Ln(10);"""

new_block = """            if (is_file($signaturePath)) {
                $hasSignature = true;
                $pdf->Ln(1); // reduced margin

                $imgWidth = 35;
                $imgHeight = 12;
                $centerX = 20 + (175.6 - $imgWidth) / 2;
                
                $currentY = $pdf->GetY();

                $pdf->Image($signaturePath, $centerX, $currentY, $imgWidth, $imgHeight, '', '', '', false, 300, '', false, false, 0, 'CM', false, false);
                $pdf->SetY($currentY + $imgHeight); // Move exactly past the image

                $pdf->SetTextColor(15, 23, 42);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->Cell(175.6, 4, $signatureName, 0, 1, 'C'); // reduced cell height
                $pdf->Ln(1); // reduced margin
            }
        }

        if (!$hasSignature) {
            $pdf->Ln(2); // reduced margin
        }

        // ── AVISO FISCAL ──
        $pdf->SetFillColor(255, 251, 235); // amber-50
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetTextColor(146, 64, 14);
        $noticeY = $pdf->GetY();
        $pdf->Cell(175.6, 6, '  Aviso fiscal: Este recibo no constituye un CFDI y no tiene validez fiscal para efectos de deducción de impuestos.', 0, 1, 'L', true);

        $pdf->Ln(2); // reduced margin

        // ── OBSERVACIONES ──
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(175.6, 6, 'Observaciones', 0, 1, 'L');
        $pdf->SetDrawColor(226, 232, 240);
        $pdf->Line(20, $pdf->GetY(), 195.6, $pdf->GetY());
        $pdf->Ln(1); // reduced margin

        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->Cell(175.6, 4, 'Conserve este recibo para sus registros. El pago se refleja en su estado de cuenta.', 0, 1, 'L');

        $pdf->Ln(4); // reduced from 10"""

if old_block in content:
    content = content.replace(old_block, new_block)
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)
    print("Success")
else:
    print("Could not find the block to replace!")
