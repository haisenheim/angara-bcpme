<div class="table-responsive" style="max-height: 60vh;">
    <table class="table table-sm table-hover sim-schedule mb-0" id="simulator-schedule">
        <thead class="table-light sticky-top">
            <tr>
                <th>{{ __('simulator::simulator.schedule.period') }}</th>
                <th>{{ __('simulator::simulator.schedule.date') }}</th>
                <th class="amount">{{ __('simulator::simulator.schedule.capital_due_start') }}</th>
                <th class="amount">{{ __('simulator::simulator.schedule.principal_paid') }}</th>
                <th class="amount">{{ __('simulator::simulator.schedule.interest_paid') }}</th>
                <th class="amount">{{ __('simulator::simulator.schedule.insurance_paid') }}</th>
                <th class="amount">{{ __('simulator::simulator.schedule.fees_paid') }}</th>
                <th class="amount">{{ __('simulator::simulator.schedule.vat_paid') }}</th>
                <th class="amount">{{ __('simulator::simulator.schedule.total_payment') }}</th>
                <th class="amount">{{ __('simulator::simulator.schedule.capital_due_end') }}</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
